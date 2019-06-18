<?php


namespace App\Tests\EventListener;


use App\Entity\Affiliate;
use App\EventListener\AffiliateTokenListener;
use App\Tests\EventListener\Stub\NonEntity;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class AffiliateTokenListenerTest extends TestCase
{
    public function testAffiliateTokenIsSet(): void
    {
        // Mock up an EntityManagerInterface object
        $em = $this->createMock(EntityManagerInterface::class);

        $args = new LifecycleEventArgs(new Affiliate(), $em);

        $listener = new AffiliateTokenListener();
        $listener->prePersist($args);

        $this->assertNotEmpty($args->getObject()->getToken());
    }

    public function testNonAffiliateIgnored(): void
    {
        // Mock up an EntityManagerInterface object
        $em = $this->createMock(EntityManagerInterface::class);

        $args = new LifecycleEventArgs(new NonEntity(), $em);

        $listener = new AffiliateTokenListener();
        $listener->prePersist($args);

        $this->assertEmpty($args->getObject()->getToken());
    }
}