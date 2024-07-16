<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Recipe;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

       public function findAllWithRating(?User $user)
       {
            $query = $this->createQueryBuilder('r')
                ->addSelect('avg(comments.rating) as avg_rating')
                ->addSelect('count(comments.rating) as count_rating');

                //Si l'utilisateur n'est pas connecté on ignore les recettes favorites
                if($user !== null)
                {
                    //Si la recette est favorite, true ou false
                    $query->addSelect('CASE WHEN COUNT(userFavoriteRecipes) > 0 THEN true ELSE false END AS isFavorite')
                    ->leftJoin('r.userFavoriteRecipes', 'userFavoriteRecipes', 'WITH', 'userFavoriteRecipes.user = :user')
                    ->setParameter('user', $user->getId());
                }
                
                $query->groupBy('r.id')
                ->leftJoin('r.comments', 'comments')
                ->orderBy('r.id', 'ASC')
                ->getQuery();
           ;

           return $query;
       }

    //    public function findOneBySomeField($value): ?Recipe
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
