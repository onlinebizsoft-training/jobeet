<?php

namespace App\Service;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * CategoryService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Creates a new category entity
     *
     * @param string $name
     * @return Category
     */
    public function create(string $name): Category
    {
        $category = new Category();
        $category->setName($name);
        $this->em->persist($category);
        $this->em->flush();
        return $category;
    }
}