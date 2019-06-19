<?php


namespace App\Tests\Entity;


use App\Entity\Affiliate;
use App\Entity\Category;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class AffiliateTest extends TestCase
{
    /**
     * Test getter and setter of Affiliate Entity
     * Test properties, addCategory
     */
    public function testGetterSetterAndDefaultValues(): void
    {
        $firstCategory = (new Category())->setName('_FIRST_');
        $secondCategory = (new Category())->setName('_SECOND_');

        $collection = new ArrayCollection([$firstCategory, $secondCategory]);

        $testAffiliate = (new Affiliate())
            ->setUrl('http://url.com')
            ->setEmail('hello@email.com')
            ->setToken('_TOKEN_')
            ->setActive(true)
            ->addCategory($firstCategory)
            ->addCategory($secondCategory);

        $this->assertEquals('http://url.com', $testAffiliate->getUrl());
        $this->assertEquals('hello@email.com', $testAffiliate->getEmail());
        $this->assertEquals('_TOKEN_', $testAffiliate->getToken());
        $this->assertTrue($testAffiliate->isActive());
        $this->assertNull($testAffiliate->getId());
        $this->assertNull($testAffiliate->getCreatedAt());
        $this->assertEquals($collection, $testAffiliate->getCategories());
    }

    /**
     * Test remove categories
     */
    public function testRemoveCategories(): void
    {
        $firstCategory = (new Category())->setName('_FIRST_');
        $secondCategory = (new Category())->setName('_SECOND_');

        $testAffiliate = (new Affiliate())
            ->addCategory($firstCategory)
            ->addCategory($secondCategory);

        $testAffiliate->removeCategory($firstCategory)
                      ->removeCategory($secondCategory);

        $this->assertEmpty($testAffiliate->getCategories());
    }

    /**
     * Test createAt is created after each prePersist
     */
    public function testPrePersistSetCreateAt(): void
    {
        $testAffiliate = new Affiliate();
        $this->assertNull($testAffiliate->getCreatedAt());
        $testAffiliate->prePersist();
        $this->assertInstanceOf(\DateTime::class, $testAffiliate->getCreatedAt());
    }
}