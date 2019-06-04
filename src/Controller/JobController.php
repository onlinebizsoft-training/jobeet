<?php


namespace App\Controller;

use App\Entity\Job;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("job")
 */
class JobController extends AbstractController
{
    /**
     * Lists all job entities.
     *
     * @Route("/", name="job.list")
     *
     * @return Response
     */
    public function list(): Response
    {
        // getDoctrine()->getRepository(): fetch Job Entity.
        $jobs = $this->getDoctrine()->getRepository(Job::class)->findAll();
        // Render view list job, attach param jobs.
        return $this->render('job/list.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * Finds and displays a job entity.
     *
     * @Route("/{id}", name="job.show")
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