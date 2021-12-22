<?php

namespace App\Validator;

use App\Entity\ProductVariation;
use App\Repository\ProductVariationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use http\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VariantAttributesComboExistValidator extends ConstraintValidator
{

    public function __construct(ProductVariationRepository $productVariationRepository)
    {

    }

    public function validate($attributes, Constraint $constraint)
    {
        if (null === $attributes || '' === $attributes) {
            return;
        }
        if(!$this->context->getObject() instanceof ProductVariation){
            throw new InvalidArgumentException('this validator is only to be used in the product variants class');
        }

        $existingAttributeCombinations = $this->getExistingAttributeCombinationsIdAsKey();
        $newAttributeCombination = $this->getNewAttributeCombination($attributes);

        foreach ($existingAttributeCombinations as $existingVariationId => $existingAttributeCombination){

            sort($existingAttributeCombination);
            sort($newAttributeCombination);

            if($this->context->getObject()->getId() !== null){
                if($this->context->getObject()->getId() === $existingVariationId){
                    continue;
                }
            }
            if($existingAttributeCombination === $newAttributeCombination){
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }

    }

    private function getNewAttributeCombination($attributes): array{
        $newAttributeCombination = [];

        foreach ($attributes as $attribute){
            $newAttributeCombination[] = $attribute->getName();
        }

        return $newAttributeCombination;
    }

    private function getExistingAttributeCombinationsIdAsKey(): array{
        $existingAttributeCombinations = [];

        foreach($this->context->getObject()->getProduct()->getProductVariations() as $existingVariation){
            $attributeCombination = [];
            foreach ($existingVariation->getAttributes() as $attribute){
                $attributeCombination[] = $attribute->getName();
            }
            $existingAttributeCombinations[$existingVariation->getId()] = $attributeCombination;
        }

        return $existingAttributeCombinations;
    }

}
