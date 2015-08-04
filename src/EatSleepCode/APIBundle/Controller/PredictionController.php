<?php

namespace EatSleepCode\APIBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use EatSleepCode\APIBundle\Entity\User;
use EatSleepCode\APIBundle\Entity\Prediction;

/**
 * @Route("/predictions", service="api_prediction_controller")
 */
class PredictionController extends APIController {

    /**
     * @Route(name="api_post_predictions")
     * @Method({"POST"})
     */
    public function postPredictionAction(Request $request, User $user) {
        $predictions = json_decode($request->getContent());
        foreach ($predictions as $prediction) {
            if (ctype_digit($prediction->homeScore) && ctype_digit($prediction->awayScore)) {
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

        return $this->_JSONResponse(array('msg' => 'Predictions saved'));
    }

}
