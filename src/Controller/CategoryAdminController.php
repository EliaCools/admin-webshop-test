<?php

namespace App\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryAdminController extends CRUDController
{
    protected function preCreate(Request $request, object $object): ?Response
    {
        $this->denyAccessUnlessGranted('ROLE_EDITOR');
        return parent::preCreate($request, $object);
    }
}
