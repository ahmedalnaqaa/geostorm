<?php

namespace Todo\Bundle\ListBundle\Form;

use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')
                ->add('description')
                ->add('created_at');
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'todo_bundle_list_bundle_list_type';
    }

    /**
     * @Route("")
     * @param Request $request
     * @return mixed
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(ListType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            dump($form->getData());die;
        }

        return $this->render('list/new.html.twig',[
           'listForm' => $form->createView()
        ]);
    }
}
