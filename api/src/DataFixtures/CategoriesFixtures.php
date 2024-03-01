<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categoriesToCreate = [
            [
                'name' => 'Starter',
                'description' => 'Starters are a great way to kick off a meal. These recipes are perfect for a special occasion.'
            ],
            [
                'name' => 'Main',
                'description' => 'These main courses are perfect for a special occasion.'
            ],
            [
                'name' => 'Dessert',
                'description' => 'These desserts are perfect for a special occasion.'
            ],
        ];

        foreach ($categoriesToCreate as $categoryToCreate) {
            $category = new Category();
            $category->setName($categoryToCreate['name']);
            $category->setDescription($categoryToCreate['description']);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
