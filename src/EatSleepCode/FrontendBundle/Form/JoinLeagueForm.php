<?php

namespace EatSleepCode\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class JoinLeagueForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('code', 'text')
            ->add('save', 'submit', array(
                'label' => 'Join',
                'attr' => array('class' => 'right'),
            ));
    }

    public function getName() {
        return 'joinLeagueForm';
    }

}
