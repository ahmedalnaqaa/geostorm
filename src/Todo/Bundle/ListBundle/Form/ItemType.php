<?php

namespace Todo\Bundle\ListBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content')
                ->add('order')
                ->add('list',ListType::class,[
                    'data_class' => null
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Todo\Bundle\ListBundle\Entity\Item'
        ));
    }

    public function getName()
    {
        return 'item';
    }
}
