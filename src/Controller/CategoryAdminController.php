<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use Sonata\AdminBundle\Bridge\Exporter\AdminExporter;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryAdminController extends CRUDController
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    protected function preList(Request $request): ?Response
    {
        $this->assertObjectExists($request);

        $this->admin->checkAccess('list');

        $listMode = $request->get('_list_mode');

        if (null !== $listMode) {
            $this->admin->setListMode($listMode);
        }

        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();

        $tree = $this->categoryService->getCategoryTree();


        // set the theme for the current Admin Form
        $this->setFormTheme($formView, $this->admin->getFilterTheme());

        $template = 'admin/CategoryAdmin/list.html.twig';

        if ($this->container->has('sonata.admin.admin_exporter')) {
            $exporter = $this->container->get('sonata.admin.admin_exporter');
            \assert($exporter instanceof AdminExporter);
            $exportFormats = $exporter->getAvailableFormats($this->admin);
        }

        return $this->renderWithExtraParams($template, [
            'action' => 'list',
            'form' => $formView,
            'datagrid' => $datagrid,
            'csrf_token' => $this->getCsrfToken('sonata.batch'),
            'export_formats' => $exportFormats ?? $this->admin->getExportFormats(),
            'tree' => $tree
        ]);
    }


    public function insertNewCategoryApiAction(Request $request, CategoryRepository $categoryRepository)
    {
        $content = $request->toArray();

        $referenceCategoryId = $content["referencedId"];
        $newCategoryName = $content["name"];

        if ($content['action'] === "addAfter") {
            $referenceCategory = $categoryRepository->find($referenceCategoryId);

            $newCategory = new Category();
            $newCategory->setName($newCategoryName);
            $newCategory->setParent($referenceCategory->getParent());

            $em = $this->getDoctrine()->getManager();
            $em->persist($newCategory);
            $em->flush();
        }

        $newPersistedCategory = $categoryRepository->findLastInserted();

        return $this->json($newPersistedCategory);
    }
}
