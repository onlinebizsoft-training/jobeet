<?php


namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class JobFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        // Insert values for each job
        $jobExpired = new Job();
        // Reference to object Category has column "name" = "Programming"
        $jobExpired->setCategory($manager->merge($this->getReference('category-programming')));
        $jobExpired->setType('full-time');
        $jobExpired->setCompany('Sensio Labs');
        $jobExpired->setLogo('sensio-labs.gif');
        $jobExpired->setUrl('http://www.sensiolabs.com/');
        $jobExpired->setPosition('Web Developer Expired');
        $jobExpired->setLocation('Paris, France');
        $jobExpired->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
        $jobExpired->setHowToApply('Send your resume to lorem.ipsum [at] dolor.sit');
        $jobExpired->setPublic(true);
        $jobExpired->setActivated(true);
        $jobExpired->setToken('job_expired');
        $jobExpired->setEmail('job@example.com');
        $jobExpired->setExpiresAt(new \DateTime('-10 days'));

        // Put all of changes into queue
        $manager->persist($jobExpired);

        for ($i = 1; $i <= 25; $i++) {
            $jobProgramming = new Job();
            $jobProgramming->setCategory($manager->merge($this->getReference('category-programming')));
            $jobProgramming->setType('full-time');
            $jobProgramming->setCompany('Company ' . $i);
            $jobProgramming->setPosition('Web Developer');
            $jobProgramming->setLocation('Paris, France');
            $jobProgramming->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit.');
            $jobProgramming->setHowToApply('Send your resume to lorem.ipsum [at] dolor.sit');
            $jobProgramming->setPublic(true);
            $jobProgramming->setActivated(true);
            $jobProgramming->setToken('job_' . $i);
            $jobProgramming->setEmail('job@example.com');

            $manager->persist($jobProgramming);
        }

        // Execute all of changes in queue
        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        // Set CategoryFixtures is executed earlier than JobFixtures
        return [
            CategoryFixtures::class,
        ];
    }
}