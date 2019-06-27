<?php


namespace App\Controller;


use App\Entity\Affiliate;
use App\Form\AffiliateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("affiliate/")
 *
 * Class AffiliateController
 * @package App\Controller
 */
class AffiliateController extends AbstractController
{
    /**
     * Creates a new affiliate entity
     *
     * @Route("create", name="affiliate.create", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $affiliate = new Affiliate();
        $form = $this->createForm(AffiliateType::class, $affiliate, ['csrf_protection' => false]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $affiliate->setActive(false);
            $em->persist($affiliate);
            $em->flush($affiliate);
            return $this->redirectToRoute('affiliate.wait');
        }

        $cache = new FilesystemAdapter('', 120);
        $item = $cache->getItem('formAffiliateCreate');
        if (!$item->isHit()) {
            // Set form into item
            $item->set($this->render('affiliate/form.html.twig', ['form' => $form->createView()]));
            $cache->save($item);
        }
        if ($cache->hasItem('formAffiliateCreate')) {
            // Remove header HTTP
            $view = explode('GMT', $item->get());
            // Render Form in Create template
            return $this->render('affiliate/create.html.twig', [
                'form' => trim($view[1])
            ]);
        }
    }

    /**
     * @Route("wait", name="affiliate.wait", methods={"GET"})
     *
     * @return Response
     */
    public function wait()
    {
        return $this->render('affiliate/wait.html.twig');
    }
}