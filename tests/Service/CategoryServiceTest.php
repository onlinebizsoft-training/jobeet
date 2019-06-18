<?php


namespace App\Tests\Service;

use App\Entity\Category;
use App\Service\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CategoryServiceTest extends TestCase
{
    public function testCreateCategory(): void
    {
        // Mock up an EntityManagerInterface object
        $em = $this->getMockBuilder(EntityManagerInterface::class)
                   ->getMock();

        $em->expects($this->once())
           ->method('persist')
           ->with($this->callback(function ($category) {
               return $category instanceof Category && $category->getName() === '_NAME_';
           }));

        $em->expects($this->once())
           ->method('flush');

        $service = new CategoryService($em);
        $category = $service->create('_NAME_');
        $this->assertEquals('_NAME_', $category->getName());
    }
}