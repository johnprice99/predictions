<?php

namespace EatSleepCode\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

class RegistrationController extends BaseController {

	private function userAlreadyLoggedIn() {
		$securityContext = $this->container->get('security.authorization_checker');
		if ($securityContext->isGranted('ROLE_USER')) {
			return true;
		}

		return false;
	}

	public function registerAction(Request $request) {
		if ($this->userAlreadyLoggedIn()) {
			$router = $this->container->get('router');

			return new RedirectResponse($router->generate('fos_user_profile_edit'), 302);
		}

		return parent::registerAction($request);
	}

	public function checkEmailAction() {
		if ($this->userAlreadyLoggedIn()) {
			$router = $this->container->get('router');

			return new RedirectResponse($router->generate('fos_user_profile_edit'), 302);
		}

		return parent::checkEmailAction();
	}

	public function confirmAction(Request $request, $token) {
		if ($this->userAlreadyLoggedIn()) {
			$router = $this->container->get('router');

			return new RedirectResponse($router->generate('fos_user_profile_edit'), 302);
		}

		return parent::confirmAction($request, $token);
	}
}
