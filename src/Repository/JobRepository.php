<?php


namespace App\Repository;

use App\Entity\Category;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;

class JobRepository extends EntityRepository
{
    // Check job is actived or not, if not yet return null
    public function findActiveJob(int $id)
    {
        return $this->createQueryBuilder('j')
                    ->where('j.id = :id')
                    ->andWhere('j.expiresAt > :date')
                    ->setParameter('id', $id)
                    ->setParameter('date', new \DateTime())
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    // Get active jobs in category
    public function getPaginatedActiveJobsByCategoryQuery(Category $category): AbstractQuery
    {
        return $this->createQueryBuilder('j')
                    ->where('j.category = :category')
                    ->andWhere('j.expiresAt > :date')
                    ->setParameter('category', $category)
                    ->setParameter('date', new \DateTime())
                    ->getQuery();
    }
}