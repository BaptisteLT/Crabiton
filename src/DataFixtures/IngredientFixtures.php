<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\DataFixtures\AllergenFixtures;
use Doctrine\Persistence\ObjectManager;

class IngredientFixtures extends AbstractFixtures
{
    public const INGREDIENT_REFERENCE = 'Ingredient';

    public const INGREDIENT_NUMBER = 100;

    public function load(ObjectManager $manager): void
    {
        //On génère 100 ingrédients
        for($i = 0; $i< self::INGREDIENT_NUMBER; $i++)
        {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->words(2, true));
            $this->addReference(self::INGREDIENT_REFERENCE . $i, $ingredient);

            $randomAllergenNumber = random_int(0,3);

            //On ajoute 0 à 3 allergènes à l'ingrédient
            for($j = 0; $j<$randomAllergenNumber; $j++)
            {
                //On va trouver aléatoirement un allergène
                $ingredient->addAllergen($this->getReference(AllergenFixtures::ALLERGENS[random_int(0, count(AllergenFixtures::ALLERGENS)-1)]));
            }
     
            $manager->persist($ingredient);
            $manager->flush();
        }        
    }

    public function getDependencies()
    {
        return [
            AllergenFixtures::class
        ];
    }
}
