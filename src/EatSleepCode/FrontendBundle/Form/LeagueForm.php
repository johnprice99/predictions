<?php

namespace EatSleepCode\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LeagueForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', 'text')
            ->add('save', 'submit', array(
                'label' => 'Create',
                'attr' => array('class' => 'right'),
            ));
    }

    public function getName() {
        return 'leagueForm';
    }

}
