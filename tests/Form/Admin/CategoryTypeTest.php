<?php


namespace App\Tests\Form\Admin;


use App\Entity\Category;
use App\Form\Admin\CategoryType;
use App\Tests\Form\AbstractTypeTest;

class CategoryTypeTest extends AbstractTypeTest
{
    public function testSubmitValidData(): void
    {
        $testCategory = new Category();
        $form = $this->factory->create(CategoryType::class, $testCategory);

        $submittedData = [
            'name' => '_NAME_'
        ];

        $view = $form->createView();
        $children = $view->children;

        // Compare $keys of $submittedData with $keys of $form
        foreach (array_keys($submittedData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    /**
     * {@inheritDoc}
     * @return array
     */
    protected function getEntityClassNames(): array
    {
        return [Category::class];
    }
}