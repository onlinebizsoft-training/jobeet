<?php


namespace App\Tests\EventListener;


use App\Entity\Affiliate;
use App\Entity\Job;
use App\EventListener\AffiliateTokenListener;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class AffiliateTokenListenerTest extends TestCase
{
    public function testAffiliateTokenIsSet(): void
    {
        // Mock up an EntityManagerInterface object
        $em = $this->getMockBuilder(EntityManagerInterface::class)
                   ->getMock();

        $args = new LifecycleEventArgs(new Affiliate(), $em);

        $listener = new AffiliateTokenListener();
        $listener->prePersist($args);

        $this->assertNotEmpty($args->getObject()->getToken());
    }

    public function testNonAffiliateIgnored(): void
    {
        // Mock up an EntityManagerInterface object
        $em = $this->getMockBuilder(EntityManagerInterface::class)
                   ->getMock();

        // Mock up an Job object
        $job = $this->getMockBuilder(Job::class)
                    ->getMock();

        // Unit test is fail if setToken method of Job is used
        $job->expects($this->never())
            ->method('setToken');

        $args = new LifecycleEventArgs($job, $em);

        $listener = new AffiliateTokenListener();
        $listener->prePersist($args);

        $this->assertEmpty($args->getObject()->getToken());
    }
}