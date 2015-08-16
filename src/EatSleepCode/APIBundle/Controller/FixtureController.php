<?php

namespace EatSleepCode\APIBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use EatSleepCode\APIBundle\Entity\User;

/**
 * @Route("/fixtures", service="api_fixture_controller")
 */
class FixtureController extends APIController {

    /**
     * @Route("/{matchDay}", name="api_fixtures", requirements={"matchDay": "\d+"})
     * @Method({"GET"})
     */
    public function fixtureListAction($matchDay) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
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
                'date' => $fixture->getDate()->setTimezone(new \DateTimeZone('Europe/London'))->format('Y-m-d H:i:s'),
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
            $fixtures[$fixture->getDate()->format('Y-m-d')][] = $f;
        }

        $orderedFixtures = array();
        foreach ($fixtures as $date => $games) {
            $orderedFixtures[] = array(
                'date' => $date,
                'fixtures' => $games,
            );
        }
        return $this->_JSONResponse($orderedFixtures);
    }

}
