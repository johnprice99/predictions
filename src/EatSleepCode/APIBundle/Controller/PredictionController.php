<?php

namespace EatSleepCode\APIBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use EatSleepCode\APIBundle\Entity\User;
use EatSleepCode\APIBundle\Entity\Prediction;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * @Route("/predictions", service="api_prediction_controller")
 */
class PredictionController extends APIController {

    /**
     * Submit predictions for a given user (logged in via OAuth).
     * If both scores are blank, the prediction will be removed (or deleted if being updated to blank values).
     *
     * @Route(name="api_post_predictions")
     * @Method({"POST"})
     * @ApiDoc(
     *  resource=true,
     *  description="Save predictions",
     *  statusCodes={
     *      200="Predictions have been saved"
     *  }
     * )
     */
    public function postPredictionAction(Request $request) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $predictions = json_decode($request->getContent());
        foreach ($predictions as $prediction) {
            if ((ctype_digit($prediction->homeScore) && ctype_digit($prediction->awayScore)) || ($prediction->homeScore == "" && $prediction->awayScore == "")) {
                //check that fixture exists and has not been played
                $fixture = $this->entityManager->getRepository('EatSleepCodeAPIBundle:Fixture')->findOneById($prediction->fixture);
                //also check that the fixture isn't playing today or later
                $today = new \DateTime();
                if ($fixture->getHomeScore() < 0 && $fixture->getDate()->setTimezone(new \DateTimeZone('Europe/London'))->setTime(0,0) > $today->setTimezone(new \DateTimeZone('Europe/London'))) {
                    //check if prediction exists - if so, update else add
                    $existingPrediction = $this->entityManager->getRepository('EatSleepCodeAPIBundle:Prediction')->findOneBy(
                        array('fixture' => $fixture, 'user' => $user)
                    );
                    if ($prediction->homeScore != "" && $prediction->awayScore != "") { //if we are making a prediction
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
                    elseif ($existingPrediction != null) { //if we are clearing an existing prediction
                        $this->entityManager->remove($existingPrediction);
                    }
                }
            }
        }
        $this->entityManager->flush();

        return $this->_JSONResponse(array('msg' => 'Predictions saved'));
    }

}
