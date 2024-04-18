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

        for ($j = 0; $j < 20; $j++) {
            $recipe = new Recipe();
            $recipe->setName($faker->sentence(3));
            $recipe->setInstructions($faker->paragraph(4));
            $recipe->setPreparationTime($faker->numberBetween(10, 60));
            $recipe->setDifficulty($faker->randomElement(['easy', 'medium', 'hard']));
            $recipe->setCategory($faker->randomElement($categories));

            for ($i = 0; $i < $faker->numberBetween(3, 5); $i++) {
                $quantity = new Quantity();
                $quantity->setIngredient($faker->randomElement($ingredients));
                $quantity->setRecipe($recipe);
                $quantity->setAmount($faker->numberBetween(1, 200));
                $quantity->setUnit($faker->randomElement(['g', 'kg', 'tbsp', 'tsp', 'cup', 'ml', 'l', 'clove', 'can', 'bunch', 'pinch', 'slice', 'whole']));
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
