<?php

namespace EatSleepCode\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use EatSleepCode\AdminBundle\Form\SettingForm;

/**
 * @Route("/settings")
 * @Security("has_role('ROLE_ADMIN')")
 */
class SettingController extends Controller {

    /**
     * @Route("/", name="admin_setting_list")
     * @Template
     */
    public function settingListAction() {
        return array(
            'settings' => $this->getDoctrine()->getRepository('EatSleepCodeAdminBundle:Setting')->findAll()
        );
    }

    /**
     * @Route("/edit/{id}", name="admin_edit_setting", requirements={"id": "\d+"})
     * @Template
     */
    public function editAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $setting = $em->getRepository('EatSleepCodeAdminBundle:Setting')->findOneById($id);
        if ($setting == null) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(new SettingForm(), $setting);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($form->getData());
            $em->flush();
            return $this->redirectToRoute('admin_edit_setting', array('id' => $id));
        }

        return array(
            'form' => $form->createView(),
        );
    }

}
