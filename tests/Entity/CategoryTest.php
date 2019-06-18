<?php


namespace App\Tests\Entity;


use App\Entity\Affiliate;
use App\Entity\Category;
use App\Entity\Job;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testGetterSetterAndDefaultValues(): void
    {
        $testCategory = (new Category())
            ->setName('_NAME_')
            ->setSlug('_SLUG_');

        $this->assertEquals('_NAME_', $testCategory->getName());
        $this->assertEquals('_SLUG_', $testCategory->getSlug());
        $this->assertNull($testCategory->getId());
    }

    public function testAffiliateCollectionManipulations(): void
    {
        $testCategory = new Category();
        $emptyCollection = new ArrayCollection();
        $this->assertEquals($emptyCollection, $testCategory->getAffiliates());

        $firstAffiliate = (new Affiliate())->setEmail('first@email.com');
        $secondAffiliate = (new Affiliate())->setEmail('second@email.com');
        $testCategory->addAffiliate($firstAffiliate)
                     ->addAffiliate($secondAffiliate);
        $this->assertEquals(new ArrayCollection([$firstAffiliate, $secondAffiliate]), $testCategory->getAffiliates());

        $testCategory->removeAffiliate($firstAffiliate)
                     ->removeAffiliate($secondAffiliate);
        $this->assertEquals($emptyCollection, $testCategory->getAffiliates());
    }

    public function testJobCollectionManipulations(): void
    {
        $testCategory = new Category();
        $emptyCollection = new ArrayCollection();
        $this->assertEquals($emptyCollection, $testCategory->getJobs());

        // Not activated, not expired
        $firstJob = (new Job())
            ->setDescription('_FIRST_')
            ->setActivated(false)
            ->setExpiresAt(new \DateTime('+3 days'));
        // Activated, not expired
        $secondJob = (new Job())
            ->setDescription('_SECOND_')
            ->setActivated(true)
            ->setExpiresAt(new \DateTime('+3 days'));
        // Activated, Expired
        $thirdJob = (new Job())
            ->setDescription('_THIRD_')
            ->setActivated(true)
            ->setExpiresAt(new \DateTime('-3 days'));
        $testCategory->addJob($firstJob)
                     ->addJob($secondJob)
                     ->addJob($thirdJob);
        $this->assertEquals(new ArrayCollection([$firstJob, $secondJob, $thirdJob]), $testCategory->getJobs());
        // getActiveJobs gets activated and not expired jobs
        $this->assertEquals($secondJob, $testCategory->getActiveJobs()->first());
        $this->assertEquals(1, $testCategory->getActiveJobs()->count());

        $testCategory->removeJob($firstJob)
                     ->removeJob($secondJob)
                     ->removeJob($thirdJob);
        $this->assertEquals($emptyCollection, $testCategory->getJobs());
    }
}