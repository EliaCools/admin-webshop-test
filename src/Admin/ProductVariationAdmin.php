<?php

namespace App\Admin;

use App\Entity\AttributeGroup;
use App\Entity\AttributeValue;
use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityRepository;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ProductVariationAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $builder = $form->getFormBuilder();
        $ff = $builder->getFormFactory();

        $form
            ->add('quantity')
            ->add('price')
            ->add('attributes', EntityType::class, [
                "class" => AttributeGroup::class,
                'mapped' => false,
                "multiple" => true,
                "query_builder" => function(EntityRepository $er){
                    return  $er->createQueryBuilder('a');
                },


            ])
            ->add('sku');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->add('quantity')
            ->add('product')
            ->add('attributes')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'edit' => [],
                    'delete' => []
                ]
            ]);
    }

    protected function configureTabMenu(ItemInterface $menu, string $action, ?AdminInterface $childAdmin = null): void
    {


        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');



    }

    protected function configureQuery(ProxyQueryInterface $query): ProxyQueryInterface
    {
        $query = parent::configureQuery($query);
        $query->andWhere("o.isBaseProduct = false");

        return $query;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('attributes');
    }

}
