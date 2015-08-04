<?php

namespace EatSleepCode\AdminBundle\Controller;

use EatSleepCode\UserBundle\Controller\ProfileController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/users")
 * @Security("has_role('ROLE_ADMIN')")
 */
class UserController extends BaseController {

    /**
     * @Route("/", name="user_list")
     * @Template
     */
    public function userListAction() {
        return array(
            'users' => $this->getDoctrine()->getRepository('EatSleepCodeAPIBundle:User')->findAll()
        );
    }

    /**
     * @Route("/edit/{id}", name="user_edit")
     * @Template()
     */
    public function userEditAction($id, Request $request) {
        $currentUser = $this->getUser();

        if ($currentUser->getID() == $id) { //users cannot edit themselves!
            throw $this->createAccessDeniedException('You cannot edit your own user');
        }

        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
        $form = $this->createForm('user', $user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $userManager->updateUser($user);
            $this->get('session')->getFlashBag()->add('success', $user->getFullName() . ' has been updated');
            return $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
        }
        return array(
            'form' => $form->createView(),
            'user' => $user,
        );
    }

    /**
     * @Route("/users/deactivate/{id}", name="user_deactivate")
     * @Template()
     */
    public function userDeactivateAction($id) {
        $currentUser = $this->getUser();
        if ($currentUser->getID() == $id) { //users cannot edit themselves!
            throw $this->createAccessDeniedException('You cannot deactivate your own user');
        }

        $this->_setUserStatus($id, false);
        return $this->redirect($this->generateUrl('user_list'));
    }

    /**
     * @Route("/users/activate/{id}", name="user_activate")
     * @Template()
     */
    public function userActivateAction($id) {
        $currentUser = $this->getUser();
        if ($currentUser->getID() == $id) { //users cannot edit themselves!
            throw $this->createAccessDeniedException('You cannot deactivate your own user');
        }
        $this->_setUserStatus($id, true);
        return $this->redirect($this->generateUrl('user_list'));
    }

    private function _setUserStatus($id, $active) {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('EatSleepCodeAPIBundle:User')->find($id);
        $user->setEnabled($active);
        $em->flush();
        $this->get('session')->getFlashBag()->add('success', $user->getFullName() . ' has been ' . (!$active ? 'de' : '') . 'activated');
    }



//	function deleteDirectory($dir) {
//		if (!file_exists($dir)) return true;
//		if (!is_dir($dir)) return unlink($dir);
//		foreach (scandir($dir) as $item) {
//			if ($item == '.' || $item == '..') continue;
//			if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) return false;
//		}
//		return rmdir($dir);
//	}

	/**
	 * @Route("/profile/delete", name="account_delete")
	 */
	public function deleteAction() {
        echo 'move to the frontend bundle';
        die();
		//first - remove any files associated with this user
//		$this->deleteDirectory($this->getUploadRootDir().$this->getUser()->getId());

//		$userManager = $this->get('fos_user.user_manager');
//		$userManager->deleteUser($this->getUser());
//
//		$this->container->get('security.token_storage')->setToken(NULL);
//
//		return $this->redirect($this->generateUrl('fe_fixture_list', array('matchDay' => 1)));
	}

}
