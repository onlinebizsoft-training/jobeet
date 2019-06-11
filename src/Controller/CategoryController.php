<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Job;
use App\Service\JobHistoryService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends AbstractController
{
    /**
     * Finds and displays a category entity
     *
     * @Route("/category/{slug}/{page}", name="category.show", methods="GET",
     *          defaults={"page": 1}, requirements={"page" = "\d+"})
     *
     * @param Category $category
     *
     * @return Response
     */
    public function show(Category $category, int $page, PaginatorInterface $paginator, JobHistoryService $jobHistoryService): Response
    {
        $activeJobs = $paginator->paginate(
            $this->getDoctrine()->getRepository(Job::class)->getPaginatedActiveJobsByCategoryQuery($category),
            $page,  // page
            $this->getParameter('max_jobs_on_category')  // elements per page
        );
        return $this->render('category/show.html.twig', [
            'category' => $category,
            'activeJobs' => $activeJobs,
            'historyJobs' => $jobHistoryService->getJobs()
        ]);
    }
}
