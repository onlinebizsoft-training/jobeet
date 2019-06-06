<?php


namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    // Find categories have active job
    public function findWithActiveJobs()
    {
        return $this->createQueryBuilder('c')
                    ->select('c')
                    ->innerJoin('c.jobs', 'j')
                    ->where('j.expiresAt > :date')
                    ->setParameter('date', new \DateTime())
                    ->getQuery()
                    ->getResult();
    }
}