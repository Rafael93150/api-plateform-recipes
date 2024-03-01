<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class IngredientsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $ingredientsToCreate = [
            'Spaghetti',
            'Pancetta',
            'Garlic',
            'Olive oil',
            'Parmesan',
            'Eggs',
            'Beef mince',
            'Onion',
            'Carrot',
            'Celery',
            'Tomato purée',
            'Red wine',
            'Chopped tomatoes',
            'Beef stock',
            'Dried oregano',
            'Bay leaf',
        ];

        foreach ($ingredientsToCreate as $ingredientToCreate) {
            $ingredient = new Ingredient();
            $ingredient->setName($ingredientToCreate);
            $manager->persist($ingredient);
        }

        $manager->flush();
    }
}
