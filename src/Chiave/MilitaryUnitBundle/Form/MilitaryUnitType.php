<?php

namespace Chiave\MilitaryUnitBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MilitaryUnitType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('unitId')
            ->add('name')
            ->add('submit',
                'submit',
                array(
                    'label' => 'Wyślij',
                )
            )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Chiave\MilitaryUnitBundle\Document\MilitaryUnit',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'chiave_militaryunit_militaryunits';
    }
}
