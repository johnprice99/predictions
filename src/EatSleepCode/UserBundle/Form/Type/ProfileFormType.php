<?php

namespace EatSleepCode\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType {

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
			));

        parent::buildForm($builder, $options);
		$builder
			->remove('username')  // we use email as the username
			->remove('email') //override with different email field
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
			->add('clearProfilePicture', 'checkbox', array(
				'label' => 'Clear Picture',
				'required' => false
			))
			->add('profilePictureFile', 'file', array(
				'label' => 'Upload a custom avatar',
				'required' => false
			));
    }

    public function getName() {
        return 'eatsleepcode_user_profile';
    }
}