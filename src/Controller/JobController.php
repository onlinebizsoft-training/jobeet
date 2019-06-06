<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Job;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class JobController extends AbstractController
{
    /**
     * Lists all job entities.
     *
     * @Route("/", name="job.list")
     *
     * @return Response
     */
    public function list(EntityManagerInterface $em): Response
    {
        // Execute get catogories have active jobs in CategoryRepository
        $categories = $em->getRepository(Category::class)->findWithActiveJobs();

        // Render view list job, attach param jobs.
        return $this->render('job/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Finds and displays a job entity.
     *
     * @Route("job/{id}", name="job.show", requirements={"id" = "\d+"})
     *
     * @Entity("job", expr="repository.findActiveJob(id)")
     * If job expried -> findActiveJob return null
     *
     * @param Job $job
     *
     * @return Response
     */
    public function show(Job $job): Response
    {
        // Render view show a job by id, attach param job.
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }
}