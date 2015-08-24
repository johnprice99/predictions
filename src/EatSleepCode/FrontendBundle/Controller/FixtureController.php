<?php

namespace EatSleepCode\FrontendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use EatSleepCode\FrontendBundle\Form\PredictionsForm;

class FixtureController extends FrontendController {

    /**
     * @Route("/fixtures/{matchDay}", name="fe_fixture_list", defaults={"matchDay" = 0})
     * @Template
     * @Security("has_role('ROLE_USER')")
     */
    public function listAction($matchDay, Request $request) {
        $latestCompletedMatchday = $this->getDoctrine()->getRepository('EatSleepCodeAdminBundle:Setting')->findOneByKey('completed_matchday')->getValue();
        if ($matchDay === 0) {
            $matchDay = $latestCompletedMatchday + 1;
        }
        $allFixtures = json_decode($this->get('api_fixture_controller')->fixtureListAction($matchDay)->getContent());

        $form = $this->createForm(new PredictionsForm());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $myPredictions = array();
            foreach ($request->request->get('fixture') as $fixtureId => $pred) {
                $pred['fixture'] = $fixtureId;
                $myPredictions[] = $pred;
            }
            $post = $this->_createPOSTRequest($myPredictions);
            $result = $this->get('api_prediction_controller')->postPredictionAction($post);
            if ($result->getStatusCode() === 200) {
                $this->addFlash('success', 'Your predictions have been saved');
            }
            return $this->redirectToRoute('fe_fixture_list', array('matchDay' => $matchDay));
        }

        return array(
            'matchDay' => $matchDay,
            'latestCompletedMatchday' => $latestCompletedMatchday,
            'fixtures' => $allFixtures,
            'form' => $form->createView(),
        );
    }

}
