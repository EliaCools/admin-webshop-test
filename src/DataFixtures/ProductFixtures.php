<?php

namespace App\DataFixtures;

use App\Entity\AttributeGroup;
use App\Entity\AttributeValue;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductVariation;
use Brick\Money\Money;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();

        $category = new Category();
        $category->setName('Clothes');

        $attributeGroup = new AttributeGroup();
        $attributeGroup->setName('Shirt Size');

        $attributeValue1 = new AttributeValue();
        $attributeValue1->setName('S');
        $attributeValue2 = new AttributeValue();
        $attributeValue2->setName('M');
        $attributeValue3 = new AttributeValue();
        $attributeValue3->setName('L');

        $manager->persist($attributeValue1);
        $manager->persist($attributeValue2);
        $manager->persist($attributeValue3);

        $attributeGroup->addAttributeValue($attributeValue1);
        $attributeGroup->addAttributeValue($attributeValue2);
        $attributeGroup->addAttributeValue($attributeValue3);

        $manager->persist($category);
        $manager->persist($attributeGroup);

        $product = new Product();
        $product->setName("shirt")
            ->setCategory($category);
        $productVariation1 = new ProductVariation();
        $productVariation1->setPrice(Money::of("12", "EUR"))
            ->setSku("123A")
            ->setIsBaseProduct(true)
            ->setQuantity(12)
            ->addAttribute($attributeValue1);
        $product->setBaseProduct($productVariation1);

        $manager->persist($product);
        $manager->flush();

    }
}
