<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UsersFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = [
            [
                'firstname' => 'Jane',
                'lastname' => 'Doe',
                'email' => 'jane.doe@gmail.com',
                'roles' => ['ROLE_USER'],
                'password' => 'password',
            ],
            [
                'firstname' => 'John',
                'lastname' => 'Doe',
                'email' => 'john.doe@gmail.com',
                'roles' => ['ROLE_USER', 'ROLE_ADMIN'],
                'password' => 'password',
            ],
        ];

        foreach ($users as $userToCreate) {
            $user = new User();
            $user->setFirstname($userToCreate['firstname']);
            $user->setLastname($userToCreate['lastname']);
            $user->setEmail($userToCreate['email']);
            $user->setRoles($userToCreate['roles']);
            $user->setPassword($userToCreate['password']);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
