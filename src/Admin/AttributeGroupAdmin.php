<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class AttributeGroupAdmin extends AbstractAdmin
{
    public function configureFormFields(FormMapper $form): void
    {
        $form->add('name');
    }

    public function configureListFields(ListMapper $list): void
    {
        $list->add('name');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('name');
    }
}
