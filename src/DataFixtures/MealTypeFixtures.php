<?php

namespace App\DataFixtures;

use App\Entity\MealType;
use Doctrine\Persistence\ObjectManager;

class MealTypeFixtures extends AbstractFixtures
{
    public const MEAL_TYPES = [
        'breakfast',
        'brunch',
        'elevenses',
        'lunch',
        'tea',
        'supper',
        'dinner'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach(self::MEAL_TYPES as $meal)
        {
            $mealType = new MealType();
            $mealType->setName($meal);
            $this->addReference($meal, $mealType);

            $manager->persist($mealType);

        }


        $manager->flush();
    }
    
}
