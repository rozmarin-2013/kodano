<?php

namespace App\Controller;

use App\DTO\Input\AddCategoriesInput;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class AddCategories
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly EntityManagerInterface $entityManager
    ){ }

    public function __invoke(Product $product, AddCategoriesInput $addCategoriesInput): Product
    {
        $product->removeAllCategories();

        foreach ($addCategoriesInput->categoryIds ?? [] as $categoryId) {
            $category = $this->categoryRepository->find($categoryId);
            if ($category) {
                $product->addCategory($category);
            }
        }

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }
}