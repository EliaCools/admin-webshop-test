<?php

namespace App\Admin\blockService;

use App\Repository\ProductRepository;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

class statsBlockService extends AbstractBlockService
{

    private ProductRepository $productRepository;

    public function __construct(Environment $twig, ProductRepository $productRepository)
    {
        parent::__construct($twig);
        $this->productRepository = $productRepository;
    }

    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'title' => 'Statistics',
            'template' => 'admin/Block/stats.html.twig',
        ]);
    }

    public function execute(BlockContextInterface $blockContext, ?Response $response = null): Response
    {
        $productAmount = count($this->productRepository->findAll());

        return $this->renderResponse($blockContext->getTemplate(), [
            'block' => $blockContext->getBlock(),
            'settings' => $blockContext->getSettings(),
            'productAmount' => $productAmount
        ], $response);
    }
}
