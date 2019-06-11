<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Job;
use App\Form\JobType;
use App\Service\JobHistoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\FileUploader;

class JobController extends AbstractController
{
    /**
     * Lists all job entities.
     *
     * @Route("/", name="job.list", methods="GET")
     *
     * @param EntityManagerInterface $em
     * @param JobHistoryService $jobHistoryService
     * @return Response
     */
    public function list(EntityManagerInterface $em, JobHistoryService $jobHistoryService): Response
    {
        // Execute get catogories have active jobs in CategoryRepository
        $categories = $em->getRepository(Category::class)->findWithActiveJobs();
        // Render view list job, attach param jobs.
        return $this->render('job/list.html.twig', [
            'categories' => $categories,
            'historyJobs' => $jobHistoryService->getJobs()
        ]);
    }

    /**
     * Finds and displays a job entity.
     *
     * @Route("job/{id}", name="job.show", methods="GET", requirements={"id" = "\d+"})
     *
     * @Entity("job", expr="repository.findActiveJob(id)")
     * If job expried -> findActiveJob return null
     *
     * @param Job $job
     * @return Response
     */
    public function show(Job $job, JobHistoryService $jobHistoryService): Response
    {
        $jobHistoryService->addJob($job);
        // Render view show a job by id, attach param job.
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }

    /**
     * Create a new job entity
     *
     * @Route("job/create", name="job.create", methods={"GET","POST"})
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        // Maps request data to form
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Get data from logo field in form
            $logoFile = $form->get('logo')->getData();
            if ($logoFile instanceof UploadedFile) {
                // Upload file
                $fileName = $fileUploader->upload($logoFile);
                $job->setLogo($fileName);
            }
            $em->persist($job);
            $em->flush();
            return $this->redirectToRoute('job.preview', ['token' => $job->getToken()]);
        }
        return $this->render('job/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Edit existing job entity
     *
     * @Route("job/{token}/edit", name="job.edit", methods={"GET","POST"}, requirements={"token" = "\w+"})
     *
     * @param Request $request
     * @param Job $job
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function edit(Request $request, Job $job, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $logoFile = $form->get('logo')->getData();
            if ($logoFile instanceof UploadedFile) {
                $fileName = $fileUploader->upload($logoFile);
                $job->setLogo($fileName);
            }
            // $em->persist($job): not necessary, because job entity is exist
            $em->flush();
            return $this->redirectToRoute('job.preview', ['token' => $job->getToken()]);
        }
        return $this->render('job/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Finds and displays the preview page for a job entity.
     *
     * @Route("job/{token}", name="job.preview", methods="GET", requirements={"token" = "\w+"})
     *
     * @param Job $job
     * @return Response
     */
    public function preview(Job $job): Response
    {
        $deleteForm = $this->createFormBuilder()
                           ->setAction($this->generateUrl('job.delete', ['token' => $job->getToken()]))
                           ->setMethod('DELETE')
                           ->getForm();
        $publishForm = $this->createFormBuilder(['token' => $job->getToken()])
                            ->setAction($this->generateUrl('job.publish', ['token' => $job->getToken()]))
                            ->setMethod('POST')
                            ->getForm();
        return $this->render('job/show.html.twig', [
            'job' => $job,
            'hasControlAccess' => true, // if user has control access, user can go to preview page
            'deleteForm' => $deleteForm->createView(),
            'publishForm' => $publishForm->createView()
        ]);
    }

    /**
     * Delete a job entity.
     *
     * @Route("job/{token}/delete", name="job.delete", methods="DELETE", requirements={"token" = "\w+"})
     *
     * @param Request $request
     * @param Job $job
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(Request $request, Job $job, EntityManagerInterface $em): Response
    {
        // Create a form with delete method to delete a job entity
        $form = $this->createFormBuilder()
                     ->setAction($this->generateUrl('job.delete', ['token' => $job->getToken()]))
                     ->setMethod('DELETE')
                     ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($job);
            $em->flush();
            $this->addFlash('notice', 'Your job was deleted');
        }
        return $this->redirectToRoute('job.list');
    }

    /**
     * Publish a job entity.
     *
     * @Route("job/{token}/publish", name="job.publish", methods="POST", requirements={"token" = "\w+"})
     *
     * @param Request $request
     * @param Job $job
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function publish(Request $request, Job $job, EntityManagerInterface $em): Response
    {
        // Create a form with post method to active a job entity
        $form = $this->createFormBuilder(['token' => $job->getToken()])
                     ->setAction($this->generateUrl('job.publish', ['token' => $job->getToken()]))
                     ->setMethod('POST')
                     ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $job->setActivated(true);
            $em->flush();
            $this->addFlash('notice', 'Your job was published');
        }
        return $this->redirectToRoute('job.preview', ['token' => $job->getToken()]);
    }
}