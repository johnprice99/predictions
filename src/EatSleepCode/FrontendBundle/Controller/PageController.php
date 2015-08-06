<?php

namespace EatSleepCode\FrontendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use EatSleepCode\FrontendBundle\Form\InviteForm;

class PageController extends FrontendController {

    /**
     * @Route("/", name="fe_homepage")
     * @Security("has_role('ROLE_USER')")
     */
    public function homepageAction() {
        return $this->redirectToRoute("fe_fixture_list");
    }

    /**
     * @Route("/invite", name="fe_invite")
     * @template
     * @Security("has_role('ROLE_USER')")
     */
    public function inviteAction(Request $request) {
        $form = $this->createForm(new InviteForm());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            print_r($data);
            die();

//            $post = $this->_createPOSTRequest(array('name' => $data['name']));
//            $result = $this->get('api_league_controller')->createLeagueAction($post, $this->getUser());
//
//            switch ($result->getStatusCode()) {
//                case 201:
//                    $this->addFlash('success', 'New league created successful');
//                    return $this->redirectToRoute('fe_league_table', array('leagueId' => json_decode($result->getContent())->id));
//                    break;
//                default:
//                    $this->addFlash('error', json_decode($result->getContent())->message);
//                    break;
//            }
//            return $this->redirectToRoute('fe_join_league');
        }

        return array(
            'form' => $form->createView(),
        );
    }

}
