<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Customer;
use Locale;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class CustomerAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('email')
            ->add('roles')
            ->add('password')
            ->add('isVerified')
            ->add('firstName')
            ->add('lastName')
            ->add('createdAt')
            ->add('orderCount')
            ->add('lastOrderDate')
            ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('email')
            ->add('isVerified')
            ->add('firstName')
            ->add('lastName')
            ->add('createdAt')
            ->add('orderCount')
            ->add('lastOrderDate')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }


    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('email')
            ->add('isVerified')
            ->add('firstName')
            ->add('lastName')
            ->add('createdAt')
            ->add('orderCount')
            ->add('lastOrderDate')
            ;
    }

    public function toString(object $object): string
    {
        return $object instanceof Customer ? $object->getFirstName() : 'Customer';
    }
}
