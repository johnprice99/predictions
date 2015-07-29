<?php

namespace EatSleepCode\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('email', 'text', array(
				'attr' => array(
					'class' => 'email',
					'minLength' => 5,
					'maxLength' => 255,
					'placeholder' => 'email@example.com',
					'data-msg-required' => 'Please enter an email address',
				)
			))
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
			));

		if ($options['includePassword'] === true) {
			$builder->add('plainPassword', 'password', array(
				'label' => 'Password',
				'attr' => array(
					'minLength' => 8,
					'maxLength' => 16,
					'data-msg-required' => 'Please enter a password',
				),
			));
		}

		$builder
			->add('enabled', 'checkbox', array(
				'label' => 'Enabled',
				'label_attr' => array(
					'class' => 'checkboxLabel',
				),
				'required' => false,
			));

		$builder->add('save', 'submit');
	}

	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults(array(
			'includePassword' => false,
		));
	}

	public function getName() {
		return 'user';
	}
}
