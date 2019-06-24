<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Job;
use App\Service\JobHistoryService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
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
     * @param int $page
     * @param PaginatorInterface $paginator
     * @param JobHistoryService $jobHistoryService
     * @return Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function show(Category $category, int $page, PaginatorInterface $paginator, JobHistoryService $jobHistoryService): Response
    {
        $cache = new FilesystemAdapter('', 60);
        $item = $cache->getItem('showActiveJobs' . $category->getName());
        if (!$item->isHit()) {
            $activeJobs = $paginator->paginate(
                $this->getDoctrine()->getRepository(Job::class)->getPaginatedActiveJobsByCategoryQuery($category),
                $page,  // page
                $this->getParameter('max_jobs_on_category')  // elements per page
            );
            $item->set($activeJobs);
            $cache->save($item);
        }
        if ($cache->hasItem('showActiveJobs' . $category->getName())) {
            return $this->render('category/show.html.twig', [
                'category' => $category,
                'activeJobs' => $item->get(),
                'historyJobs' => $jobHistoryService->getJobs()
            ]);
        }
    }
}
