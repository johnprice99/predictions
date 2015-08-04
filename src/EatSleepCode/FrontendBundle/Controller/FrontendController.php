<?php

namespace EatSleepCode\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class FrontendController extends Controller {

    protected function _createPOSTRequest($content) {
        return new Request(array(), array(), array(), array(), array(), $_SERVER, json_encode($content));
    }

    /**
     * @Route("/", name="fe_homepage")
     * @Security("has_role('ROLE_USER')")
     */
    public function homepageAction() {
        return $this->redirectToRoute("fe_fixture_list");
    }

}
