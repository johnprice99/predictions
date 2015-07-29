<?php

namespace EatSleepCode\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('firstName', 'text', array(
				'attr' => array(
					'minLength' => 2,
					'maxLength' => 100,
					'data-msg-required' => 'Please enter a first name',
				)
			))
            ->add('lastName', 'text', array(
				'attr' => array(
					'minLength' => 2,
					'maxLength' => 100,
					'data-msg-required' => 'Please enter a last name',
				)
			))
			->add('email', 'email', array(
				'label' => 'form.email',
				'translation_domain' => 'FOSUserBundle',
				'attr' => array(
					'class' => 'email',
					'minLength' => 5,
					'maxLength' => 255,
					'placeholder' => 'email@example.com',
					'data-msg-required' => 'Please enter your email address',
				)
			))
			->add('plainPassword', 'repeated', array(
				'type' => 'password',
				'options' => array('translation_domain' => 'FOSUserBundle'),
				'first_options' => array(
					'label' => 'form.password',
					'attr' => array(
						'minLength' => 8,
						'maxLength' => 16,
						'data-msg-required' => 'Please enter a password',
					),
				),
				'second_options' => array(
					'label' => 'form.password_confirmation',
					'attr' => array(
						'minLength' => 8,
						'maxLength' => 16,
						'equalTo' => "#fos_user_registration_form_plainPassword_first",
						'data-msg-required' => 'Please confirm your password',
						'data-msg-equalto' => 'The passwords do not match',
					),
				),
				'invalid_message' => 'fos_user.password.mismatch',
			));
    }

    public function getName() {
        return 'eatsleepcode_user_registration';
    }
}