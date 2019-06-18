<?php


namespace App\Tests\Form;


use App\Entity\Affiliate;
use App\Form\AffiliateType;

class AffiliateTypeTest extends AbstractTypeTest
{
    public function testSubmitValidData(): void
    {
        $testAffiliate = new Affiliate();
        $form = $this->factory->create(AffiliateType::class, $testAffiliate);

        $submittedData = [
            'url' => '_URL_',
            'email' => 'hello@email.com',
            'categories' => [new Affiliate()]
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
        return [Affiliate::class];
    }
}