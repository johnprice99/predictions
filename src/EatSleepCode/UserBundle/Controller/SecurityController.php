<?php

namespace EatSleepCode\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController {

	private function userAlreadyLoggedIn() {
		$securityContext = $this->container->get('security.authorization_checker');
		if ($securityContext->isGranted('ROLE_USER')) {
			return true;
		}

		return false;
	}

	public function loginAction(Request $request) {
		if ($this->userAlreadyLoggedIn()) {
			$router = $this->container->get('router');

			return new RedirectResponse($router->generate('fos_user_profile_edit'), 302);
		}

		return parent::loginAction($request);
	}

	public function checkAction() {
		if ($this->userAlreadyLoggedIn()) {
			$router = $this->container->get('router');

			return new RedirectResponse($router->generate('fos_user_profile_edit'), 302);
		}

		return parent::checkAction();
	}
}
