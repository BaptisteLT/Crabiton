<?php

namespace App\DataFixtures;

use App\Entity\Allergen;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AllergenFixtures extends Fixture
{
    public const ALLERGENS = [
        'Mollusques',
        'Lupin',
        'Anhydride sulfureux et sulfites',
        'Graines de sésame',
        'Moutarde',
        'Céleri',
        'Fruits à coque',
        'Lait',
        'Soja',
        'Arachides',
        'Poissons',
        'Oeufs',
        'Crustacés',
        'Céréales contenant du gluten'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach(self::ALLERGENS as $allergenName)
        {
            $allergen = new Allergen();
            $manager->persist($allergen);
            $this->addReference($allergenName, $allergen);

            $allergen->setName($allergenName);
        }
       
        $manager->flush();
    }
}
