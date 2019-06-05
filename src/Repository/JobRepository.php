<?php


namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class JobRepository extends EntityRepository
{
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
}