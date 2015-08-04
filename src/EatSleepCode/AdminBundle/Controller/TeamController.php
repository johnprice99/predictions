<?php

namespace EatSleepCode\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use EatSleepCode\APIBundle\Entity\Team;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/teams")
 * @Security("has_role('ROLE_ADMIN')")
 */
class TeamController extends Controller {

    /**
     * @Route("/", name="team_list")
     * @Template
     */
    public function teamListAction() {
        return array(
            'teams' => $this->getDoctrine()->getRepository('EatSleepCodeAPIBundle:Team')->findAllOrderedByName()
        );
    }

    /**
     * @Route("/download", name="team_download")
     */
    public function downloadTeamsAction() {
        $em = $this->getDoctrine()->getManager();

        $response = $this->get('api_client')->get('/alpha/soccerseasons/398/teams');
        if ($response->getStatusCode() === 200) {
            foreach (json_decode($response->getBody())->teams as $team) {
                $entity = $em->getRepository('EatSleepCodeAPIBundle:Team')->findOneBy(array('url' => $team->_links->self->href));
                if ($entity == null) {
                    $newTeam = new Team();
                    $newTeam->setUrl($team->_links->self->href);
                    $newTeam->setName($team->name);
                    $em->persist($newTeam);
                }
            }
            $em->flush();
            return $this->redirect($this->generateUrl('team_list'));
        }

        return new Response('Error', $response->getStatusCode(), array('X-Status-Code' => $response->getStatusCode()));
    }

}
