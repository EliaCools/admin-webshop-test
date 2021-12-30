<?php

// src/Admin/CategoryAdmin.php

namespace App\Admin;

use App\Entity\Category;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

final class CategoryAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'category';
    protected function configureTabMenu(ItemInterface $menu, string $action, ?AdminInterface $childAdmin = null): void
    {
        if (!$childAdmin && !in_array($action, ['edit', 'show'])) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;

        $id = $admin->getRequest()->get('id');

        $menu->addChild('back to list', $admin->generateMenuUrl('list'));
        $menu->addChild('Edit Category', $admin->generateMenuUrl('edit', ['id' => $id]));


        if ($this->isGranted('LIST')) {
            $menu->addChild('Manage Products', $admin->generateMenuUrl('admin.product.list', ['id' => $id]));
        }

    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper->add('name', TextType::class);
        $formMapper->add('parent');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper->add('name');
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {

        $collection->add('goToProductList');
        $collection->add('insertNewCategoryApi', 'create/api');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper->addIdentifier('name');
    }
    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('name');
    }

    public function toString(object $object): string
    {
        return $object instanceof Category ? $object->getName() : 'Category';
    }
}
