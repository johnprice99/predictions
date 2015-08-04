<?php

namespace EatSleepCode\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/settings")
 * @Security("has_role('ROLE_ADMIN')")
 */
class SettingController extends Controller {

    /**
     * @Route("/", name="setting_list")
     * @Template
     */
    public function settingListAction() {
        return array(
            'settings' => $this->getDoctrine()->getRepository('EatSleepCodeAdminBundle:Setting')->findAll()
        );
    }

}
