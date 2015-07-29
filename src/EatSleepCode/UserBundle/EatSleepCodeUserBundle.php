<?php

namespace EatSleepCode\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EatSleepCodeUserBundle extends Bundle {

	public function getParent() {
		return 'FOSUserBundle';
	}

}
