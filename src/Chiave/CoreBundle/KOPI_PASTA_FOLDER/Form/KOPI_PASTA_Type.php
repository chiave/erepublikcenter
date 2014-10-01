<?php

namespace Chiave\NAZWABUNDLA\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

// NAZWABUNDLA -> podaj nazwę bundla w którym jesteśmy
// NAZWATYPU -> podaj ta nazwe co ma plik
// NAZWAENCJI -> podaj nazwę encji

class NAZWATYPU extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('name', null, array(
                    'attr' => array(
                        'placeholder' => 'Nazwa',
                        'label' => 'Nazwa',
            )))
                ->add('submit', 'submit', array(
                    'label' => 'Zapisz'
                        )
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Chiave\NAZWABUNDLA\Document\NAZWAENCJI',
        ));
    }

    /**
     * @return string
     */
    public function getName() {
//        return 'chiave_gamerbundle_player';
    }

}
