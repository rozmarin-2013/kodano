<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $categories = [];
        for ($i = 0; $i < 3; $i++) {
            $category = new Category();
            $category->setCode($faker->unique()->bothify('??###'));

            $manager->persist($category);
            $categories[] = $category;
        }

        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setName($faker->word())
                ->setPrice($faker->randomFloat(2, 10, 100));

            foreach ($categories as $category) {
                $product->addCategory($category);
            }

            $manager->persist($product);
        }

        $manager->flush();
    }
}

