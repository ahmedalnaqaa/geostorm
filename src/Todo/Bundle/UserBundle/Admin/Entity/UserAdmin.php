<?php

namespace Todo\Bundle\UserBundle\Admin\Entity;

use Todo\Bundle\UserBundle\Form\Type\SecurityRolesType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\Filter\NumberType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\AdminBundle\Annotation as Sonata;

/**
 * @Sonata\Admin(
 *   class="Todo\Bundle\UserBundle\Entity\User",
 *   id="todo.admin.user.user",
 *   baseControllerName="SonataAdminBundle:CRUD",
 *   group="Users",
 *   label="Users",
 *   showInDashboard=true,
 *   translationDomain="UserBundle",
 *   icon="<i class='fa fa-users'></i>",
 *   keepOpen=false,
 *   onTop=false,
 * )
 */
class UserAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('General', ['class' => 'col-md-6'])
            ->add('id', NumberType::class, array(
                'label' => 'User ID'
            ))
            ->add('username')
            ->add('email')
            ->end()
            ->with('Security', ['class' => 'col-md-6'])
            ->add('groups')
            ->add('roles')
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            //start User tab fields
            ->tab('User')
            //start general section
            ->with('General')
            ->add('username')

            ->add('email')
            ->add('plainPassword', TextType::class, [
                'required' => (!$this->getSubject() || is_null($this->getSubject()->getId())),
            ])
            // end general section
            ->end()
            //end User tab
            ->end()
            //start Security tab fields
            ->tab('Security')
            //start Status section
            ->with('Status')
            ->add('enabled', null, ['required' => false])
            ->end()
            //start groups section
            ->with('Groups')
            ->add('groups', 'sonata_type_model', [
                'required' => false,
                'expanded' => true,
                'multiple' => true,
            ])
            ->end()
            //start roles section
            ->with('Roles')
            ->add('roles', SecurityRolesType::class, [
                'expanded' => true,
                'multiple' => true,
                'required' => false,
            ])
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
            ->add('id')
            ->add('username')
            ->add('email')
            ->add('enabled')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        unset($this->listModes['mosaic']);
        $listMapper
            ->addIdentifier('id', NumberType::class, array(
                'route' => array(
                    'name' => 'show'
                )
            ))
            ->addIdentifier('username', null, array(
                'route' => array(
                    'name' => 'show'
                )
            ))
            ->add('email')
            ->add('enabled')
            ->add('lastLogin')
        ;
    }
}