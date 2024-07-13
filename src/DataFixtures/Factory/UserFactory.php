<?php

namespace App\DataFixtures\Factory;

use App\Entity\User;
use App\DataFixtures\Factory\AbstractFactory;

class UserFactory extends AbstractFactory
{
    public function create(): User
    {
        $user = new User();
        $user->setEmail(uniqid().'fixtures.user@test.fr')
             ->setPassword('testpsw')
             ->setUsername('testusername')
             ->setVerified(true);

        return $user;
    }
}
