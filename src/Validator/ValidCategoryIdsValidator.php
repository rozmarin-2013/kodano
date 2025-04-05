<?php

namespace App\Validator;

use App\Repository\CategoryRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidCategoryIdsValidator extends ConstraintValidator
{
    public function __construct(private CategoryRepository $categoryRepository)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidCategoryIds) {
            throw new UnexpectedTypeException($constraint, ValidCategoryIds::class);
        }

        $invalidIds = [];
        foreach ($value as $id) {
            if (!$this->categoryRepository->find($id)) {
                $invalidIds[] = $id;
            }
        }

        if (!empty($invalidIds)) {
            $this->context->buildViolation($constraint->message)->addViolation();;
        }
    }
}
