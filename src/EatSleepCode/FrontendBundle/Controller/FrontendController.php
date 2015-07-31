<?php

namespace EatSleepCode\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class FrontendController extends Controller {

    /**
     * @Route("/")
     * @Security("has_role('ROLE_USER')")
     */
    public function homepageAction() {
        return $this->redirectToRoute("fe_fixture_list");
    }

    /**
     * @Route("/fixtures/{matchDay}", name="fe_fixture_list", defaults={"matchDay" = 0})
     * @Template
     * @Security("has_role('ROLE_USER')")
     */
    public function fixtureListAction($matchDay, Request $request) {
        $latestCompletedMatchday = $this->getDoctrine()->getRepository('EatSleepCodeAdminBundle:Setting')->findOneByKey('completed_matchday')->getValue();
        if ($matchDay === 0) {
            $matchDay = $latestCompletedMatchday + 1;
        }
        $allFixtures = json_decode($this->get('api_controller')->fixtureListAction($matchDay, $this->getUser())->getContent());

        $form = $this->createFormBuilder()
            ->add('save', 'submit', array(
                'label' => 'Save Predictions',
                'attr' => array('class' => 'right'),
            ))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $myPredictions = array();
            foreach ($request->request->get('fixture') as $fixtureId => $pred) {
                $pred['fixture'] = $fixtureId;
                $myPredictions[] = $pred;
            }
            $post = new Request(array(), array(), array(), array(), array(), $_SERVER, json_encode($myPredictions));
            $result = $this->get('api_controller')->postPredictionAction($post, $this->getUser());
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

    /**
     * @Route("/standings", name="fe_standings")
     * @Template
     */
    public function standingsAction() {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("
            SELECT CONCAT(u.firstName, ' ', u.lastName) AS user, COUNT(p.id) AS predictions, SUM(CASE p.points WHEN 1 THEN 1 ELSE 0 END) AS correct, SUM(CASE p.points WHEN 3 THEN 1 ELSE 0 END) AS exact, SUM(p.points) AS points
            FROM EatSleepCodeAPIBundle:User u
            LEFT JOIN EatSleepCodeAPIBundle:Prediction p WITH p.user = u.id
            GROUP BY u.id
            ORDER BY points DESC"
        );
        return array(
            'standings' => $query->getResult(),
        );
    }

}
