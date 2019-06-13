<?php


namespace App\Service;


use App\Entity\Affiliate;
use Symfony\Component\Templating\EngineInterface;

class MailerService
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var EngineInterface
     */
    private $templateEngine;

    /**
     * MailerService constructor.
     * @param \Swift_Mailer $mailer
     * @param EngineInterface $engine
     */
    public function __construct(\Swift_Mailer $mailer, EngineInterface $engine)
    {
        $this->mailer = $mailer;
        $this->templateEngine = $engine;
    }

    /**
     * @param Affiliate $affiliate
     */
    public function sendActivationEmail(Affiliate $affiliate): void
    {
        // Set email's content
        $message = (new \Swift_Message())
            ->setSubject('Account activation')
            ->setTo($affiliate->getEmail())
            ->setFrom('huuyen911@gmail.com')
            ->setBody(
                $this->templateEngine->render(
                    'emails/affiliate_activation.html.twig',
                    [
                        'token' => $affiliate->getToken()
                    ]
                ),
                'text/html'
            );
        // Send email
        $this->mailer->send($message);
    }
}