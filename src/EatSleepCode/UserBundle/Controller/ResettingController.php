<?php

namespace EatSleepCode\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\ResettingController as BaseController;

class ResettingController extends BaseController {

	private function userAlreadyLoggedIn() {
		$securityContext = $this->container->get('security.authorization_checker');
		if ($securityContext->isGranted('ROLE_USER')) {
			return true;
		}

		return false;
	}

	public function requestAction() {
		if ($this->userAlreadyLoggedIn()) {
			$router = $this->container->get('router');

			return new RedirectResponse($router->generate('fos_user_profile_edit'), 302);
		}

		return parent::requestAction();
	}

	public function sendEmailAction(Request $request) {
		if ($this->userAlreadyLoggedIn()) {
			$router = $this->container->get('router');

			return new RedirectResponse($router->generate('fos_user_profile_edit'), 302);
		}

		return parent::sendEmailAction($request);
	}

	public function checkEmailAction(Request $request) {
		if ($this->userAlreadyLoggedIn()) {
			$router = $this->container->get('router');

			return new RedirectResponse($router->generate('fos_user_profile_edit'), 302);
		}

		return parent::checkEmailAction($request);
	}

	public function resetAction(Request $request, $token) {
		if ($this->userAlreadyLoggedIn()) {
			$router = $this->container->get('router');

			return new RedirectResponse($router->generate('fos_user_profile_edit'), 302);
		}

		return parent::resetAction($request, $token);
	}
}
