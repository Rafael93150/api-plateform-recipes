<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
use App\Entity\Ingredient;
use App\Entity\Quantity;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class RecipesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $ingredients = $manager->getRepository(Ingredient::class)->findAll();
        $categories = $manager->getRepository(Category::class)->findAll();

        $recipesToCreate = [
            [
                'name' => 'Spaghetti Carbonara',
                'instructions' => '1. Cook the spaghetti in a large pan of boiling salted water according to the packet instructions. Meanwhile, heat the oil in a frying pan over a medium heat. Add the pancetta and fry for 2-3 minutes, or until golden-brown and crisp. Reduce the heat and add the garlic, cooking for a further 1-2 minutes. Remove from the heat and set aside.',
                'preparationTime' => 15,
                'difficulty' => 'easy',
            ],
            [
                'name' => 'Spaghetti Bolognese',
                'instructions' => '1. Heat the oil in a large pan over a medium heat. Add the onion, carrot, celery and garlic and cook for 5 minutes, or until softened. Add the beef and cook for 5 minutes, stirring until browned. Add the tomato purée and cook for 1 minute. Add the wine and cook for 2 minutes, or until reduced by half. Add the tomatoes, stock, oregano and bay leaf and bring to the boil. Reduce the heat and simmer for 1 hour, or until thickened. Remove the bay leaf and season to taste.',
                'preparationTime' => 30,
                'difficulty' => 'medium',
            ],
            [
                'name' => 'Spaghetti Aglio e Olio',
                'instructions' => '1. Cook the spaghetti in a large pan of boiling salted water according to the packet instructions. Meanwhile, heat the oil in a large frying pan over a medium heat. Add the garlic and cook for 2-3 minutes, or until golden but not browned. Remove from the heat and set aside. Drain the spaghetti and add to the garlic oil. Return to the heat and toss together. Season to taste and serve.',
                'preparationTime' => 15,
                'difficulty' => 'easy',
            ],
        ];

        foreach ($recipesToCreate as $recipeToCreate) {
            $recipe = new Recipe();
            $recipe->setName($recipeToCreate['name']);
            $recipe->setInstructions($recipeToCreate['instructions']);
            $recipe->setPreparationTime($recipeToCreate['preparationTime']);
            $recipe->setDifficulty($recipeToCreate['difficulty']);
            $recipe->setCategory($faker->randomElement($categories));

            for ($i = 0; $i < 3; $i++) {
                $quantity = new Quantity();
                $quantity->setIngredient($faker->randomElement($ingredients));
                $quantity->setRecipe($recipe);
                $quantity->setAmount(rand(1, 200));
                $quantity->setUnit(array_rand(['g', 'kg', 'tbsp', 'tsp', 'cup', 'ml', 'l', 'clove', 'can', 'bunch', 'pinch', 'slice', 'whole']));
                $manager->persist($quantity);
                $recipe->addQuantity($quantity);
            }
            $manager->persist($recipe);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            IngredientsFixtures::class,
            CategoriesFixtures::class,
        ];
    }
}
