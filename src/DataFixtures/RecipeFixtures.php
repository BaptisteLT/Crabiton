<?php
namespace App\DataFixtures;

ini_set('memory_limit', '2560M');

use App\Entity\Recipe;
use App\Entity\Comment;
use App\Entity\RecipeIngredients;
use App\DataFixtures\MealTypeFixtures;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\Factory\UserFactory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RecipeFixtures extends AbstractFixtures implements DependentFixtureInterface
{
    public function __construct(private UserFactory $userFactory){
        parent::__construct();
    }

    public function load(ObjectManager $manager): void
    {
        for($i = 0; $i<10; $i++)
        {
            $recipe = new Recipe();

            $recipe->setUser($this->getReference(UserFixtures::USER_REFERENCE));
            $recipe->setName($this->faker->words(3, true));
            $recipe->setPreparationTimeInSeconds($this->faker->numberBetween(600,6000));
            $recipe->setPeopleNumber($this->faker->numberBetween(1,6));
            $recipe->setMealType($this->getReference($this->faker->randomElement(MealTypeFixtures::MEAL_TYPES)));
            

            $ingredientsNumber = random_int(3,8);
            for($x = 0; $x < $ingredientsNumber; $x++)
            {
                $recipeIngredients = new RecipeIngredients();
                $recipeIngredients->setIngredient($this->getReference(IngredientFixtures::INGREDIENT_REFERENCE.random_int(0, IngredientFixtures::INGREDIENT_NUMBER)))
                                  ->setRecipe($recipe);

                //C'est soit l'un soit l'autre
                if($this->faker->boolean(50))
                {
                    $recipeIngredients->setQuantityMiligram(random_int(10000,200000));
                }
                else
                {
                    $recipeIngredients->setQuantityNumber(random_int(1,2));
                }

                $manager->persist($recipeIngredients);
            }
            

            
            $manager->persist($recipe);
    
            for($j = 0; $j < 10; $j++)
            {
                $user = $this->userFactory->create();

                $comment = new Comment();
                $comment->setRating(random_int(1, 5))
                        ->setText($this->faker->text(200))
                        ->setRecipe($recipe)
                        ->setOwner($user);
    
                $manager->persist($user);
                $manager->persist($comment);
            }

        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            MealTypeFixtures::class
        ];
    }
}
