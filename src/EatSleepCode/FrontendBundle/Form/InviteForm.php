<?php

namespace EatSleepCode\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class InviteForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('email_addresses', 'textarea', array(
                'label' => 'Email addresses (Maximum of 10, each on a new line)',
                'attr' => array('rows' => 10)
            ))
            ->add('save', 'submit', array(
                'label' => 'Send invites',
                'attr' => array('class' => 'right'),
            ));
    }

    public function getName() {
        return 'inviteForm';
    }

}
