<?php

namespace App\Validator;

use App\Validator\ValidCategoryIdsValidator;
use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class ValidCategoryIds extends Constraint
{
    public string $message = 'Some categories do not exist.';

    public function validatedBy(): string
    {
        return ValidCategoryIdsValidator::class;
    }
}