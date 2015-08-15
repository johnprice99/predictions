<?php

namespace EatSleepCode\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SettingForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('key', 'text')
            ->add('value', 'text')
            ->add('save', 'submit', array(
                'attr' => array('class' => 'right'),
            ));
    }

    public function getName() {
        return 'settingForm';
    }

}
