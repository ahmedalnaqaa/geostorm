<?php

namespace Todo\Bundle\ListBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title',TextType::class)
                ->add('description')
                ->add('created_at');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Todo\Bundle\ListBundle\Entity\ListItem'
        ));
    }

    public function getName()
    {
        return 'todo_bundle_list_bundle_list_type';
    }
}
