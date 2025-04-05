<?php

namespace App\Controller;

use App\DTO\Input\AddCategoriesInput;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class AddCategories
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly ProductRepository  $productRepository,
        private readonly EntityManagerInterface $entityManager
    ){ }

    public function __invoke(Product $product, AddCategoriesInput $addCategoriesInput): Product
    {
        $this->productRepository->find($product->getId())->removeAllCategories();

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