<?php

namespace EatSleepCode\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\ProfileController as BaseController;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Event\GetResponseUserEvent;

class ProfileController extends BaseController {

	protected function getUploadRootDir() {
		return __DIR__.'/../../../../web/uploads/avatars/';
	}

    public function showAction() { //do not want to allow this route, so just forward to the edit page
		$router = $this->container->get('router');
		return new RedirectResponse($router->generate('fos_user_profile_edit'), 302);
    }

	/*
	 * Overridden action to add in the avatar upload functionality
	 */
	public function editAction(Request $request) {
		$user = $this->getUser();
		if (!is_object($user) || !$user instanceof UserInterface) {
			throw new AccessDeniedException('This user does not have access to this section.');
		}

		if (!$user->isAccountNonLocked()) { //if the user is locked, log them out as they shouldn't be able to access their account
			$router = $this->container->get('router');
			return new RedirectResponse($router->generate('fos_user_security_logout'));
		}

		/** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
		$dispatcher = $this->get('event_dispatcher');

		$event = new GetResponseUserEvent($user, $request);
		$dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

		if (null !== $event->getResponse()) {
			return $event->getResponse();
		}

		/** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
		$formFactory = $this->get('fos_user.profile.form.factory');

		$form = $formFactory->createForm();
		$form->setData($user);

		$form->handleRequest($request);


		if ($form->isValid()) {

			/* --- START CUSTOM LOGIC --- */
			//check if uploading an avatar
			if ($user->getProfilePictureFile() !== null) {
				//check if this user already has a filename for an avatar - then just use that
				if ($user->getProfilePicturePath() == null) {
					$filename = sha1(md5($user->getId() . time()));
					$user->setProfilePicturePath($filename . '.' . $user->getProfilePictureFile()->guessExtension());
				}
				$user->getProfilePictureFile()->move($this->getUploadRootDir().$user->getId(), $user->getProfilePicturePath());
				$user->setProfilePictureFile(null);
			}
			elseif ($user->clearProfilePicture == 1) {
				unlink($this->getUploadRootDir().$user->getId() . '/' . $user->getProfilePicturePath());
				$user->setProfilePicturePath(null);
			}
			/* --- END CUSTOM LOGIC --- */

			/** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
			$userManager = $this->get('fos_user.user_manager');

			$event = new FormEvent($form, $request);
			$dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

			$userManager->updateUser($user);

			if (null === $response = $event->getResponse()) {
				$url = $this->generateUrl('fos_user_profile_show');
				$response = new RedirectResponse($url);
			}

			$dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

			return $response;
		}

		return $this->render('FOSUserBundle:Profile:edit.html.twig', array(
			'form' => $form->createView()
		));

    }
}
