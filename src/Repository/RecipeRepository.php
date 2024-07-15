<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

       public function findAllWithRating()
       {
            return $this->createQueryBuilder('r')
                ->addSelect('avg(comments.rating) as avg_rating')
                ->addSelect('count(comments.rating) as count_rating')
                ->groupBy('r.id')
                ->leftJoin('r.comments', 'comments')
                ->orderBy('r.id', 'ASC')
                ->getQuery();
           ;
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
