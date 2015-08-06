<?php

namespace EatSleepCode\FrontendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use EatSleepCode\FrontendBundle\Form\LeagueForm;
use EatSleepCode\FrontendBundle\Form\JoinLeagueForm;

class LeagueController extends FrontendController {

    private function _getLeagues() {
        return json_decode($this->get('api_league_controller')->getMyLeaguesAction($this->getUser())->getContent());
    }

    /**
     * @Route("/league/table/{leagueId}", name="fe_league_table", requirements={"leagueId": "\d+"})
     * @Template
     * @Security("has_role('ROLE_USER')")
     */
    public function tableAction($leagueId = 0) {
        $response = $this->get('api_league_controller')->leagueStandingsAction($leagueId, $this->getUser());
        switch ($response->getStatusCode()) {
            case 404:
                throw $this->createNotFoundException();
                break;
            case 403:
                throw $this->createAccessDeniedException();
                break;
        }

        if ($response->getStatusCode() != 404) {
            $league = json_decode($response->getContent());

            return array(
                'league' => $league,
                'leagueId' => $leagueId,
                'myLeagues' => $this->_getLeagues(),
            );
        }
        throw $this->createNotFoundException();
    }

    /**
     * @Route("/league/create", name="fe_create_league")
     * @Template
     * @Security("has_role('ROLE_USER')")
     */
    public function createAction(Request $request) {
        $form = $this->createForm(new LeagueForm());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $post = $this->_createPOSTRequest(array('name' => $data['name']));
            $result = $this->get('api_league_controller')->createLeagueAction($post, $this->getUser());

            switch ($result->getStatusCode()) {
                case 201:
                    $this->addFlash('success', 'New league created successful');
                    return $this->redirectToRoute('fe_league_table', array('leagueId' => json_decode($result->getContent())->id));
                    break;
                default:
                    $this->addFlash('error', json_decode($result->getContent())->message);
                    break;
            }
            return $this->redirectToRoute('fe_join_league');
        }

        return array(
            'form' => $form->createView(),
            'myLeagues' => $this->_getLeagues(),
        );
    }

    /**
     * @Route("/league/edit/{leagueId}", name="fe_edit_league", requirements={"leagueId": "\d+"})
     * @Template
     * @Security("has_role('ROLE_USER')")
     */
    public function editAction($leagueId, Request $request) {
        $response = $this->get('api_league_controller')->getLeagueAction($leagueId, $this->getUser());

        switch ($response->getStatusCode()) {
            case 404:
                throw $this->createNotFoundException();
                break;
            case 403:
                throw $this->createAccessDeniedException();
                break;
        }

        $league = json_decode($response->getContent());

        $form = $this->createForm(new LeagueForm());
        $form->get('name')->setData($league->name);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $post = $this->_createPOSTRequest(array('name' => $data['name']));
            $result = $this->get('api_league_controller')->editLeagueAction($leagueId, $post, $this->getUser());

            switch ($result->getStatusCode()) {
                case 200:
                    $this->addFlash('success', 'League updated');
                    return $this->redirectToRoute('fe_league_table', array('leagueId' => $leagueId));
                    break;
                default:
                    $this->addFlash('error', json_decode($result->getContent())->message);
                    break;
            }
            return $this->redirectToRoute('fe_edit_league', array('leagueId' => $leagueId));
        }

        return array(
            'form' => $form->createView(),
            'leagueId' => $leagueId,
            'myLeagues' => $this->_getLeagues(),
        );
    }

    /**
     * @Route("/league/delete/{leagueId}", name="fe_delete_league", requirements={"leagueId": "\d+"})
     * @Template
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAction($leagueId) {
        $response = $this->get('api_league_controller')->deleteLeagueAction($leagueId, $this->getUser());
        switch ($response->getStatusCode()) {
            case 404:
                throw $this->createNotFoundException();
                break;
            case 403:
                throw $this->createAccessDeniedException();
                break;
            case 204:
                $this->addFlash('success', 'League deleted');
                return $this->redirectToRoute('fe_league_table');
                break;
            default:
                $this->addFlash('error', json_decode($response->getContent())->message);
                break;
        }
        return $this->redirectToRoute('fe_league_table', array('leagueId' => $leagueId));
    }

    /**
     * @Route("/league/join", name="fe_join_league")
     * @Template
     * @Security("has_role('ROLE_USER')")
     */
    public function joinAction(Request $request) {
        $form = $this->createForm(new JoinLeagueForm());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();

            $post = $this->_createPOSTRequest(array('code' => $data['code']));
            $result = $this->get('api_league_controller')->joinLeagueAction($post, $this->getUser());

            switch ($result->getStatusCode()) {
                case 404:
                    $this->addFlash('error', 'Not a valid code');
                    break;
                case 200:
                    $joinedLeague = json_decode($result->getContent());
                    $this->addFlash('success', 'You have joined the league: ' . $joinedLeague->name);
                    return $this->redirectToRoute('fe_league_table', array('leagueId' => $joinedLeague->id));
                    break;
                default:
                    $this->addFlash('error', json_decode($result->getContent())->message);
                    break;
            }
            return $this->redirectToRoute('fe_join_league');
        }

        return array(
            'form' => $form->createView(),
            'myLeagues' => $this->_getLeagues(),
        );
    }

    /**
     * @Route("/league/leave/{leagueId}", name="fe_leave_league", requirements={"leagueId": "\d+"})
     * @Security("has_role('ROLE_USER')")
     */
    public function leaveAction($leagueId) {
        $result = $this->get('api_league_controller')->leaveLeagueAction($leagueId, $this->getUser());

        switch ($result->getStatusCode()) {
            case 200:
                $this->addFlash('success', 'You have left the league');
                $redirectParams = array();
                break;
            default:
                $this->addFlash('error', json_decode($result->getContent())->message);
                $redirectParams = array('leagueId' => $leagueId);
                break;
        }
        return $this->redirectToRoute('fe_league_table', $redirectParams);
    }

    /**
     * @Route("/league/kick/{leagueId}/{userId}", name="fe_league_kick_user", requirements={"leagueId": "\d+", "userId": "\d+"})
     * @Security("has_role('ROLE_USER')")
     */
    public function kickAction($leagueId, $userId) {
        $result = $this->get('api_league_controller')->kickAction($leagueId, $userId);

        switch ($result->getStatusCode()) {
            case 200:
                $this->addFlash('success', 'This user has been kicked from your league');
                break;
            default:
                $this->addFlash('error', json_decode($result->getContent())->message);
                break;
        }
        return $this->redirectToRoute('fe_league_table', array('leagueId' => $leagueId));
    }

}
