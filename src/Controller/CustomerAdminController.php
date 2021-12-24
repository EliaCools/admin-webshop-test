<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Customer;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CustomerAdminController extends CRUDController{

    protected function preShow(Request $request,  $object): ?Response
    {
        $this->assertObjectExists($request, true);

        $id = $request->get($this->admin->getIdParameter());
        \assert(null !== $id);

        $this->checkParentChildAssociation($request, $object);

        $this->admin->checkAccess('show', $object);

        $this->admin->setSubject($object);

        /**
         * @var Customer $customer;
         */
        $customer = $object;

        $fields = $this->admin->getShow();

        $addresses = $customer->getAddresses();


        $template = 'admin/CustomerAdmin/show.html.twig';


        return $this->renderWithExtraParams($template, [
            'action' => 'show',
            'object' => $object,
            'elements' => $fields,
            'addresses' => $addresses
        ]);
    }

}
