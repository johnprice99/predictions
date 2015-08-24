<?php

namespace EatSleepCode\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use EatSleepCode\AdminBundle\Form\SettingForm;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

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
            $this->addFlash('success', 'Settings saved');
            return $this->redirectToRoute('admin_edit_setting', array('id' => $id));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/tokens/clear}", name="admin_clear_stale_tokens")
     */
    public function clearStaleTokens() {
        $application = new Application($this->get('kernel'));
        $application->setAutoExit(false);
        $application->run(new ArrayInput(array('command' => 'fos:oauth-server:clean')));
        $this->addFlash('success', 'Stale tokens cleaned');
        return $this->redirectToRoute('admin_setting_list');
    }

}
