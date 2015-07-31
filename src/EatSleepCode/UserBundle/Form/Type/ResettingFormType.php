<?php

namespace EatSleepCode\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ChangePasswordFormType as BaseType;

class ResettingFormType extends BaseType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add('plainPassword', 'repeated', array(
			'type' => 'password',
			'options' => array('translation_domain' => 'FOSUserBundle'),
			'first_options' => array(
				'label' => 'form.new_password',
				'attr' => array(
					'minLength' => 8,
					'maxLength' => 16,
					'data-msg-required' => 'Please enter a new password',
				),
			),
			'second_options' => array(
				'label' => 'form.new_password_confirmation',
				'attr' => array(
					'minLength' => 8,
					'maxLength' => 16,
					'equalTo' => "#fos_user_resetting_form_plainPassword_first",
					'data-msg-required' => 'Please confirm your new password',
					'data-msg-equalto' => 'The new passwords do not match',
				),
			),
			'invalid_message' => 'fos_user.password.mismatch',
		));
	}

	public function getName() {
		return 'eatsleepcode_user_resetting';
	}
}