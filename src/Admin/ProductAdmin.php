<?php

namespace App\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Form\ProductImageType;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\AdminType;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\FieldDescription\FieldDescription;
use Sonata\DoctrineORMAdminBundle\FieldDescription\FieldDescriptionFactory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Security;
use function Sodium\add;

final class ProductAdmin extends AbstractAdmin
{
    private Security $security;

    public function __construct(string $code, string $class, string $baseControllerName, Security $security)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->security = $security;
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->with('data', ['class' => 'col-md-9'])
            ->add('name', TextType::class)
            ->add('price', MoneyType::class)
            ->add('images', CollectionType::class, [
                    'by_reference' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => ProductImageType::class,

                ]
            )
            ->end()
            ->with('metadata', ['class' => 'col-md-3'])
            ->add('category', ModelType::class, ['class' => Category::class, 'property' => 'name'])
            ->end();
    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper->add('name')
            ->add('category'
                , null,
                ['field_type' => EntityType::class,
                    'field_options' => ['class' => Category::class, 'choice_label' => 'name']
                ])
            ->add('price',);
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper->addIdentifier('name')
            ->addIdentifier('category.name')
            ->addIdentifier('price')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'edit' => [],
                    'delete' => []
                ]
            ]);

    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('name')
             ->add('productVariations')
            ->add('images');
    }

    protected function configureDashboardActions(array $actions): array
    {
        parent::configureDashboardActions($actions);
        if (!$this->security->isGranted('ROLE_EDITOR')) {
            unset($actions['create']);
        }

        return $actions;
    }

    protected function configureTabMenu(ItemInterface $menu, string $action, ?AdminInterface $childAdmin = null): void
    {
        if (!$childAdmin && !in_array($action, ['edit', 'show'])) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');

        $menu->addChild('Manage product variations', $admin->generateMenuUrl('admin.productvariation.list', ['id' => $id]));

    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {

    }


    public function toString(object $object): string
    {
        return $object instanceof Product ? $object->getName() : 'Product';
    }


}
