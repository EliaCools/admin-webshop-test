<?php

namespace App\Tests;

use App\Entity\Product;
use App\Entity\ProductVariation;
use PHPUnit\Framework\TestCase;

Final class ProductTest extends TestCase
{
    public function testGetBaseProductVariation()
    {
        $baseProductVariation = new ProductVariation();
        $baseProductVariation->setSku("TEST1");
        $productVariation1 = new ProductVariation();
        $productVariation1->setSku("TEST1.1");

        $product = new Product();
        $product->addProductVariation($productVariation1);
        $product->setBaseProduct($baseProductVariation);


        self::assertSame($baseProductVariation, $product->getBaseProduct());
    }
}
