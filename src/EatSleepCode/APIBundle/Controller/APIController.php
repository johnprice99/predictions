<?php

namespace EatSleepCode\APIBundle\Controller;

use EatSleepCode\APIBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use EatSleepCode\APIBundle\Entity\Prediction;

/**
 * @Route("/api", service="api_controller")
 */
class APIController extends Controller {

    protected $entityManager;

    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    private function _JSONResponse($json) {
        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/fixtures/{matchDay}", name="api_fixtures")
     * @Method({"GET"})
     */
    public function fixtureListAction($matchDay, User $user) {
        $fixtures = array();
        foreach ($this->entityManager->getRepository('EatSleepCodeAPIBundle:Fixture')->findAllByMatchDayOrderedByHomeTeam($matchDay) as $fixture) {

            $myPrediction = $this->entityManager->getRepository('EatSleepCodeAPIBundle:Prediction')->findOneBy(
                array('fixture' => $fixture, 'user' => $user)
            );
            $f = array(
                'id' => $fixture->getId(),
                'homeTeam' => $fixture->getHomeTeam()->getName(),
                'awayTeam' => $fixture->getAwayTeam()->getName(),
                'homeScore' => null,
                'awayScore' => null,
                'date' => $fixture->getDate()->format('Y-m-d H:i:s'),
                'played' => $fixture->getPlayed(),
            );
            if ($fixture->getPlayed()) {
                $f['homeScore'] = $fixture->getHomeScore();
                $f['awayScore'] = $fixture->getAwayScore();
            }
            if ($myPrediction !== null) {
                $f['prediction'] = array(
                    'homeScore' => $myPrediction->getHomeScore(),
                    'awayScore' => $myPrediction->getAwayScore(),
                    'points' => $myPrediction->getPoints(),
                );
            }
            $fixtures[] = $f;
        }
        return $this->_JSONResponse(json_encode($fixtures));
    }

    /**
     * @Route("/predictions", name="api_post_predictions")
     * @Method({"POST"})
     */
    public function postPredictionAction(Request $request, User $user) {
        $predictions = json_decode($request->getContent());
        foreach ($predictions as $prediction) {
            if ($prediction->homeScore != "") {
                //check that fixture exists and has not been played
                $fixture = $this->entityManager->getRepository('EatSleepCodeAPIBundle:Fixture')->findOneById($prediction->fixture);
                //also check that the fixture isn't playing today or later
                $today = new \DateTime();
                if ($fixture->getHomeScore() < 0 && $fixture->getDate()->setTime(0,0) > $today) {
                    //check if prediction exists - if so, update else add
                    $existingPrediction = $this->entityManager->getRepository('EatSleepCodeAPIBundle:Prediction')->findOneBy(
                        array('fixture' => $fixture, 'user' => $user)
                    );
                    if ($existingPrediction == null) {
                        $newPrediction = new Prediction();
                        $newPrediction->setUser($user);
                        $newPrediction->setFixture($fixture);
                        $newPrediction->setHomeScore($prediction->homeScore);
                        $newPrediction->setAwayScore($prediction->awayScore);
                        $this->entityManager->persist($newPrediction);
                    }
                    else {
                        $existingPrediction->setHomeScore($prediction->homeScore);
                        $existingPrediction->setAwayScore($prediction->awayScore);
                        $this->entityManager->persist($existingPrediction);
                    }
                }
            }
        }
        $this->entityManager->flush();

        return $this->_JSONResponse(json_encode(array('msg' => 'Predictions saved')));
    }

}
