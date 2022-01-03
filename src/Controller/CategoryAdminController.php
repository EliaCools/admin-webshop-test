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
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class CategoryAdminController extends CRUDController
{
    private CategoryService $categoryService;
    private SerializerInterface $serializer;


    public function __construct(CategoryService $categoryService, SerializerInterface $serializer )
    {
        $this->categoryService = $categoryService;

        $this->serializer = $serializer;
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

        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);
        $objectArray = $serializer->serialize($tree, 'json');


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
            'tree' => $objectArray
        ]);
    }


    public function insertNewCategoryApiAction(Request $request, CategoryRepository $categoryRepository)
    {
        $content = $request->toArray();
        $content;
        $referenceCategoryId = $content["referencedId"];
        $newCategoryName = $content["name"];

        $referenceCategory = $categoryRepository->find($referenceCategoryId);

        $newCategory = new Category();
        $newCategory->setName($newCategoryName);
        if ($content['action'] === "addAfter") {
            $newCategory->setParent($referenceCategory->getParent());
        }
        if ($content['action'] === 'addSub') {
            $newCategory->setParent($referenceCategory);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($newCategory);
        $em->flush();

        $newPersistedCategory = $categoryRepository->findLastInserted();

        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getName();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([$normalizer], [$encoder]);

        return $this->json($newPersistedCategory, 200, [], ['groups' => 'add-category-response']);
    }

    public function deleteCategoryAndAllChildrenApiAction($id, CategoryRepository $categoryRepository)
    {

        $category = $categoryRepository->find($id);

        if($category === null){
            return $this->json("category with id " . $id . "does not exist", "400");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return $this->json("category with id" . $id. "removed succesfully");
    }
}
