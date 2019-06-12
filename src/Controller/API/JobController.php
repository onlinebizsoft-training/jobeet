<?php


namespace App\Controller\API;


use App\Entity\Affiliate;
use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\Response;

class JobController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("{token}/jobs", name="api.job.list")
     *
     * @Entity("affiliate", expr="repository.findOneActiveByToken(token)")
     *
     * @param Affiliate $affiliate
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function getJobsAction(Affiliate $affiliate, EntityManagerInterface $em): Response
    {
        $jobs = $em->getRepository(Job::class)->findActiveJobsForAffiliate($affiliate);
        // view(): create view object with all jobs and 200 respone code
        // handleView(): convert view object to response object
        return $this->handleView($this->view($jobs, Response::HTTP_OK));
    }
}