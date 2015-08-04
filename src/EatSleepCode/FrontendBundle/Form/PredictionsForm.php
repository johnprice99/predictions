<?php

namespace EatSleepCode\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PredictionsForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('save', 'submit', array(
                'label' => 'Save Predictions',
                'attr' => array('class' => 'right'),
            ));
    }

    public function getName() {
        return 'predictionsForm';
    }

}
