<?php


namespace App\Controller\Admin;


use App\Entity\Category;
use App\Form\Admin\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategoryController
 * @package App\Controller\Admin
 *
 * @Route("admin/category/")
 */
class CategoryController extends AbstractController
{
    /**
     * List all categories entities.
     *
     * @Route("list", name="admin.category.list", methods="GET")
     *
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function list(EntityManagerInterface $em): Response
    {
        $categories = $em->getRepository(Category::class)->findAll();
        return $this->render('admin/category/list.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * Creates a new category
     *
     * @Route("create", name="admin.category.create", methods="GET|POST")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('admin.category.list');
        }
        return $this->render('admin/category/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Edit category
     *
     * @Route("{id}/edit", name="admin.category.edit", methods="GET|POST", requirements={"id" = "\d+"})
     *
     * @param Request $request
     * @param Category $category
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function edit(Request $request, Category $category, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin.category.list');
        }
        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);
    }

    /**
     * Delete category
     *
     * @Route("{id}/delete", name="admin.category.delete", methods="DELETE", requirements={"id" = "\d+"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Category $category
     * @return Response
     */
    public function delete(Request $request, EntityManagerInterface $em, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $em->remove($category);
            $em->flush();
        }
        return $this->redirectToRoute('admin.category.list');
    }
}