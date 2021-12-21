<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class VariantAttributesComboExist extends Constraint
{
    public $message = 'A variant with these attributes already exists';
    public $mode = 'strict'; // If the constraint has configuration options, define them as public properties


}
