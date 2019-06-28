<?php


namespace App\Controller;

use App\Controller\Traits\FormCacheTrait;
use App\Entity\Affiliate;
use App\Form\AffiliateType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    use FormCacheTrait;

    /**
     * Creates a new affiliate entity
     *
     * @Route("create", name="affiliate.create", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     * @throws InvalidArgumentException
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $affiliate = new Affiliate();
        $form = $this->createForm(AffiliateType::class, $affiliate);

        // Get token csrf
        $tokenProvider = $this->container->get('security.csrf.token_manager');
        $token = $tokenProvider->getToken('affiliate')->getValue();

        // Return token for ajax request
        if ($request->request->get('token')) {
            echo $token;
            exit;
        }

        // Handle data form
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $affiliate->setActive(false);
            $em->persist($affiliate);
            $em->flush($affiliate);
            return $this->redirectToRoute('affiliate.wait');
        }

        // Cache form
        return $this->cacheForm('formAffiliateCreate', 'affiliate', $form->createView());
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
