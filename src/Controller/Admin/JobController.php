<?php


namespace App\Controller\Admin;

use App\Entity\Job;
use App\Form\JobType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class JobController
 * @package App\Controller\Admin
 *
 * @Route("admin/job/")
 */
class JobController extends AbstractController
{
    /**
     * List all jobs entities
     *
     * @Route("list/{page}", name="admin.job.list", methods="GET", defaults={"page": 1}, requirements={"page" = "\d+"})
     *
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @param int $page
     * @return Response
     */
    public function list(EntityManagerInterface $em, PaginatorInterface $paginator, int $page): Response
    {
        $jobs = $paginator->paginate(
            $em->getRepository(Job::class)->createQueryBuilder('j'),
            $page,
            $this->getParameter('max_per_page'),
            [
                PaginatorInterface::DEFAULT_SORT_FIELD_NAME => 'j.createdAt',
                PaginatorInterface::DEFAULT_SORT_DIRECTION => 'DESC'
            ]
        );
        return $this->render('admin/job/list.html.twig', [
            'jobs' => $jobs
        ]);
    }

    /**
     * Create a new job
     *
     * @Route("create", name="admin.job.create", methods="GET|POST")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em, FileUploader $uploader): Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        $logoFile = $form->get('logo')->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            if ($logoFile instanceof UploadedFile) {
                $fileName = $uploader->upload($logoFile);
                $job->setLogo($fileName);
            }
            $em->persist($job);
            $em->flush();
            return $this->redirectToRoute('admin.job.list');
        }
        return $this->render('admin/job/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Edit job
     *
     * @Route("{id}/edit", name="admin.job.edit", methods="GET|POST", requirements={"id" = "\d+"})
     *
     * @param Request $request
     * @param Job $job
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function edit(Request $request, Job $job, EntityManagerInterface $em, FileUploader $uploader): Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);
        $logoFile = $form->get('logo')->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            if ($logoFile instanceof UploadedFile) {
                $fileName = $uploader->upload($logoFile);
                $job->setLogo($fileName);
            }
            $em->flush();
            return $this->redirectToRoute('admin.job.list');
        }
        return $this->render('admin/job/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Delete job
     *
     * @Route("{id}/delete", name="admin.job.delete", methods="DELETE", requirements={"id" = "\d+"})
     *
     * @param Request $request
     * @param Job $job
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(Request $request, Job $job, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $job->getId(), $request->request->get('_token'))) {
            $em->remove($job);
            $em->flush();
        }
        return $this->redirectToRoute('admin.job.list');
    }
}