<?php


namespace App\Tests\EventListener;


use App\Entity\Affiliate;
use App\Entity\Job;
use App\EventListener\AffiliateTokenListener;
use App\EventListener\JobTokenListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use PHPUnit\Framework\TestCase;

class JobTokenListenerTest extends TestCase
{
    public function testJobTokenListenerIsSet(): void
    {
        // Mock up an EntityManagerInterface object
        $em = $this->getMockBuilder(::class)
                   ->getMock();

        $args = new LifecycleEventArgs(new Job(), $em);

        $listener = new JobTokenListener();
        $listener->prePersist($args);

        $this->assertNotEmpty($args->getObject()->getToken());
    }

    public function testNonJobIgnored(): void
    {
        // Mock up an EntityManagerInterface object
        $em = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();

        // Mock up an Affiliate object
        $affiliate = $this->getMockBuilder(Affiliate::class)
            ->getMock();

        // Unit test is fail if setToken method of Affiliate is used
        $affiliate->expects($this->never())
            ->method('setToken');

        $args = new LifecycleEventArgs($affiliate, $em);

        $listener = new JobTokenListener();
        $listener->prePersist($args);

        $this->assertEmpty($args->getObject()->getToken());
    }
}