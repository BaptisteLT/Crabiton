<?php

namespace App\DataFixtures;

use App\Entity\Ustensil;
use Doctrine\Persistence\ObjectManager;

class UstensilFixtures extends AbstractFixtures
{
    public const USTENSIL_REFERENCE = 'Ustensil';

    public const USTENSIL_NUMBER = 20;

    public function load(ObjectManager $manager): void
    {
        //On génère 20 ustensils
        for($i = 0; $i< self::USTENSIL_NUMBER; $i++)
        {
            $ustensil = new Ustensil();
            $ustensil->setName($this->faker->words(1, true));
            $this->addReference(self::USTENSIL_REFERENCE . $i, $ustensil);

            $manager->persist($ustensil);
            $manager->flush();
        }        
    }
}
