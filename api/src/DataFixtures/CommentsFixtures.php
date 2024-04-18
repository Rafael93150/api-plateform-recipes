<?php

namespace App\DataFixtures;

use App\Entity\Recipe;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $recipes = $manager->getRepository(Recipe::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();
        
        for ($i = 0; $i < 50; $i++) {
            $comment = new Comment();
            $comment->setText($faker->paragraph(3));
            $comment->setRecipe($faker->randomElement($recipes));
            $comment->setFromUser($faker->randomElement($users));
            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            RecipesFixtures::class,
            UsersFixtures::class,
        ];
    }
}
