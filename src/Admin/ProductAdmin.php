<?php

namespace App\Admin;

use App\Entity\Category;
use App\Entity\Product;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class ProductAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->with('data', ['class' => 'col-md-9'])
                   ->add('name', TextType::class)
                   ->add('price', MoneyType::class, ['divisor' => 100])
            ->end()
            ->with('metadata', ['class' => 'col-md-3'])
            ->add('category', ModelType::class, ['class' => Category::class, 'property' => 'name'])
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper->add('name')
                        ->add('category'
                                ,null,
                                ['field_type' => EntityType::class,
                                 'field_options' => ['class' => Category::class, 'choice_label' => 'name']
                                ])
                        ->add('price');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper->addIdentifier('name')
            ->addIdentifier('category.name')
            ->addIdentifier('price');
    }

    public function toString(object $object): string
    {
        return $object instanceof Product ? $object->getName() : 'Product';
    }
}
