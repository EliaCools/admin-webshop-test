<?php

namespace App\Form;

use App\Entity\ProductVariation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductVariationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('quantity')
            ->add('priceField', MoneyType::class, [
              'label' => 'price'
            ])
            ->add('sku')
        ;
        if($options["baseProduct"] === false){
            $builder->add('attributes', EntityType::class);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductVariation::class,
            'baseProduct'=> false
        ]);
    }
}
