<?php

namespace App\Admin;

use App\Entity\Category;
use App\Entity\User;
use App\Form\RoleType;
use App\Service\RoleService;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\Security;


class UserAdmin extends AbstractAdmin
{


    private RoleService $roleService;

    public function __construct(string $code, string $class, string $baseControllerName, RoleService $roleService)
    {
        parent::__construct($code, $class, $baseControllerName);


        $this->roleService = $roleService;
    }

    protected function configureFormFields(FormMapper $form): void
    {

        $form->add('role', ChoiceType::class, [
            'multiple' => false,
            'choices' => $this->roleService->getAllTopLevelRoles()
        ]);

    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('email');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper->add('email');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('email');
    }
    public function toString(object $object): string
    {
        return $object instanceof User ? $object->getUserIdentifier() : 'Category';
    }
}
