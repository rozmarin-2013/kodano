<?php

namespace App\DTO\Input;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Validator\ValidCategoryIds;

class AddCategoriesInput
{
    #[Assert\NotBlank]
    #[ValidCategoryIds]
    #[Assert\All([
        new Assert\Type('integer')
    ])]
    #[Groups(['product:write'])]
    public ?array $categoryIds = null;
}