<?php


namespace App\Tests\Form;


use App\Entity\Category;
use App\Entity\Job;
use App\Form\JobType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class JobTypeTest extends AbstractTypeTest
{
    public function testSubmitValidData(): void
    {
        $testJob = new Job();
        $form = $this->factory->create(JobType::class, $testJob);

        $submittedData = [
            'category' => new Category(),
            'type' => 'full-time',
            'company' => '_COMPANY_',
            'logo' => $this->createMock(UploadedFile::class),
            'url' => '_URL_',
            'position' => '_POSITION_',
            'location' => '_LOCATION_',
            'description' => '_DESCRIPTION_',
            'howToAplly' => '_HOW_TO_APPLY_',
            'public' => true,
            'activated' => true,
            'email' => 'hello@email.com'
        ];

        $form->submit($submittedData);
        $this->assertTrue($form->isSynchronized());

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
        return [Job::class];
    }
}