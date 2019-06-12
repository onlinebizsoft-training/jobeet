<?php


namespace App\Repository;

use App\Entity\Affiliate;
use App\Entity\Category;
use App\Entity\Job;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Response;

class JobRepository extends EntityRepository
{
    public function findActiveJobs(int $categoryId = null)
    {
        $qb = $this->createQueryBuilder('j')
                   ->where('j.expiresAt > :date')
                   ->andWhere('j.activated = :activated')
                   ->setParameter('date', new \DateTime())
                   ->setParameter('activated', true)
                   ->orderBy('j.expiresAt', 'DESC');
        if ($categoryId) {
            $qb->andWhere('j.category = :categoryId')
               ->setParameter('categoryId', $categoryId);
        }
        return $qb->getQuery()->getResult();
    }

    // Check job is actived or not, if not yet return null
    public function findActiveJob(int $id): ?Job
    {
         return $this->createQueryBuilder('j')
                     ->where('j.id = :id')
                     ->andWhere('j.expiresAt > :date')
                     ->andWhere('j.activated = :activated')
                     ->setParameter('id', $id)
                     ->setParameter('date', new \DateTime())
                     ->setParameter('activated', true)
                     ->getQuery()
                     ->getOneOrNullResult();
    }

    // Get active jobs in category
    public function getPaginatedActiveJobsByCategoryQuery(Category $category): AbstractQuery
    {
        return $this->createQueryBuilder('j')
                    ->where('j.category = :category')
                    ->andWhere('j.expiresAt > :date')
                    ->andWhere('j.activated = :activated')
                    ->setParameter('category', $category)
                    ->setParameter('date', new \DateTime())
                    ->setParameter('activated', true)
                    ->getQuery();
    }

    // Get active jobs have relation with affiliate
    public function findActiveJobsForAffiliate(Affiliate $affiliate)
    {
        return $this->createQueryBuilder('j')
                    ->leftJoin('j.category', 'c')
                    ->leftJoin('c.affiliates', 'a')
                    ->where('a.id = :affiliate')
                    ->andWhere('j.expiresAt > :date')
                    ->andWhere('j.activated = :activated')
                    ->setParameter('affiliate', $affiliate)
                    ->setParameter('date', new \DateTime())
                    ->setParameter('activated', true)
                    ->orderBy('j.expiresAt', 'DESC')
                    ->getQuery()
                    ->getResult();
    }
}