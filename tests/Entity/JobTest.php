<?php


namespace App\Tests\Entity;


use App\Entity\Category;
use App\Entity\Job;
use PHPUnit\Framework\TestCase;

class JobTest extends TestCase
{
    public function testGetterSetterAndDefaultValues(): void
    {
        $testJob = (new Job())
            ->setType('full-time')
            ->setCompany('_COMPANY_')
            ->setUrl('http://url.com')
            ->setEmail('hello@email.com')
            ->setToken('_TOKEN_')
            ->setPosition('_POSITION_')
            ->setLocation('_LOCATION_')
            ->setDescription('_DESCRIPTION_')
            ->setHowToApply('_HOW_TO_APPLY_')
            ->setPublic(true)
            ->setActivated(true)
            ->setExpiresAt(new \DateTime());

        $this->assertInstanceOf(\DateTime::class, $testJob->getExpiresAt());

        $this->assertEquals('full-time', $testJob->getType());
        $this->assertEquals('_COMPANY_', $testJob->getCompany());
        $this->assertEquals('http://url.com', $testJob->getUrl());
        $this->assertEquals('hello@email.com', $testJob->getEmail());
        $this->assertEquals('_TOKEN_', $testJob->getToken());
        $this->assertEquals('_POSITION_', $testJob->getPosition());
        $this->assertEquals('_LOCATION_', $testJob->getLocation());
        $this->assertEquals('_DESCRIPTION_', $testJob->getDescription());
        $this->assertEquals('_HOW_TO_APPLY_', $testJob->getHowToApply());

        $this->assertTrue($testJob->isPublic());
        $this->assertTrue($testJob->isActivated());

        $this->assertNull($testJob->getId());
        $this->assertNull($testJob->getLogo());
        $this->assertNull($testJob->getCategory());

        $testJob->setLogo('_LOGO_');
        $path = $testJob->getLogoPath();
        $this->assertEquals('_LOGO_', $testJob->getLogo());
        $this->assertEquals('uploads/jobs/' . $testJob->getLogo(), $path);

        $category = (new Category())->setName('_CATEGORY_');
        $testJob->setCategory($category);
        $this->assertEquals('_CATEGORY_', $testJob->getCategoryName());
        $this->assertSame($category, $testJob->getCategory());
    }

    public function testPrePersistSetCreateAtAndUpdatedAt(): void
    {
        $testJob = $this->getMockBuilder(Job::class)
                        ->setMethods(['getCurrentDateTime'])
                        ->getMock();

        $date = new \DateTime();

        $testJob->method('getCurrentDateTime')
                ->willReturn($date);

        $this->assertNull($testJob->getCreatedAt());
        $this->assertNull($testJob->getUpdatedAt());
        $this->assertNull($testJob->getExpiresAt());

        $testJob->prePersist();

        $this->assertSame($date, $testJob->getCreatedAt());
        $this->assertSame($date, $testJob->getUpdatedAt());
        $this->assertEquals((clone $date)->modify('+30 days')->getTimestamp(), $testJob->getExpiresAt()->getTimestamp());
    }

    public function testPreUpdateSetUpdatedAt(): void
    {
        $testJob = $this->getMockBuilder(Job::class)
            ->setMethods(['getCurrentDateTime'])
            ->getMock();

        $date = new \DateTime();

        $testJob->method('getCurrentDateTime')
            ->willReturn($date);

        $this->assertNull($testJob->getUpdatedAt());

        $testJob->preUpdate();

        $this->assertSame($date, $testJob->getUpdatedAt());
    }
}