<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';

    public function __construct(private UserFactory $userFactory){}

    public function load(ObjectManager $manager): void
    {
        $user = $this->userFactory->create();
             
        $this->addReference(self::USER_REFERENCE, $user);

        $manager->persist($user);

        $manager->flush();
    }

    
}
