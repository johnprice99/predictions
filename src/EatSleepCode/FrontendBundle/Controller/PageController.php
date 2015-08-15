<?php

namespace EatSleepCode\FrontendBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class PageController extends FrontendController {

    /**
     * @Route("/", name="fe_homepage")
     * @Security("has_role('ROLE_USER')")
     */
    public function homepageAction() {
        return $this->redirectToRoute("fe_fixture_list");
    }

}
