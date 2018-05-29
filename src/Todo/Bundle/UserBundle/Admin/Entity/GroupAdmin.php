<?php

namespace Todo\Bundle\UserBundle\Admin\Entity;

use Todo\Bundle\UserBundle\Form\Type\SecurityRolesType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Annotation as Sonata;

/**
 * @Sonata\Admin(
 *   class="Todo\Bundle\UserBundle\Entity\Group",
 *   id="todo.admin.user.group",
 *   baseControllerName="SonataAdminBundle:CRUD",
 *   group="Users",
 *   label="Groups",
 *   showInDashboard=true,
 *   translationDomain="UserBundle",
 *   icon="<i class='fa fa-users'></i>",
 *   keepOpen=false,
 *   onTop=false,
 * )
 */
class GroupAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    protected $formOptions = [
        'validation_groups' => 'Registration',
    ];

    /**
     * {@inheritdoc}
     */
    public function getNewInstance()
    {
        $class = $this->getClass();

        return new $class('', []);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('roles')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('Group')
                ->with('General', ['class' => 'col-md-6'])
                    ->add('name')
                ->end()
            ->end()
            ->tab('Security')
                ->with('Roles', ['class' => 'col-md-12'])
                    ->add('roles', SecurityRolesType::class, [
                        'expanded' => true,
                        'multiple' => true,
                        'required' => false,
                    ])
                ->end()
            ->end()
        ;
    }
}
