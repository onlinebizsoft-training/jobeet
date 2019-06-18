<?php


namespace App\Tests\Service;


use App\Entity\Affiliate;
use App\Service\MailerService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Templating\EngineInterface;

class MailerServiceTest extends TestCase
{
    public function testSendActivationEmail(): void
    {
        // Mock up an Swift_Mailer object
        $mailer = $this->getMockBuilder(\Swift_Mailer::class)
                       ->disableOriginalConstructor()
                       ->getMock();

        $mailer->expects($this->once())
               ->method('send')
               ->with($this->callback(function ($message) {
                   return $message instanceof \Swift_Message && $message->getBody() === '_TEMPLATE_';
               }));

        // Mock up an EngineInterface object
        $template = $this->getMockBuilder(EngineInterface::class)
                         ->getMock();

        $template->expects($this->once())
                 ->method('render')
                 ->with(
                     $this->equalTo('emails/affiliate_activation.html.twig'),
                     $this->callback(function ($param) {
                         // Variable $param['token'] is empty '', if $param['token'] does not exist or null
                         return count($param) === 1 && $param['token'] ?? '' === '_TOKEN_';
                     })
                 )
                 ->willReturn('_TEMPLATE_');

        $affiliate = (new Affiliate())->setToken('_TOKEN_');
        $mailer = new MailerService($mailer, $template);
        $mailer->sendActivationEmail($affiliate);
    }
}