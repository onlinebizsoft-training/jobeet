<?php


namespace App\DataFixtures;

use App\Entity\Affiliate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AffiliateFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $affiliateSensioLabs = new Affiliate();
        $affiliateSensioLabs->setUrl('http://www.sensiolabs.com/');
        $affiliateSensioLabs->setEmail('contact@sensiolabs.com');
        $affiliateSensioLabs->setActive(true);
        $affiliateSensioLabs->setToken('sensio_labs');
        $affiliateSensioLabs->addCategory($manager->merge($this->getReference('category-programming')));
        $affiliateSensioLabs->addCategory($manager->merge($this->getReference('category-manager')));

        $affiliateKNPLabs = new Affiliate();
        $affiliateKNPLabs->setUrl('http://www.knplabs.com/');
        $affiliateKNPLabs->setEmail('hello@knplabs.com');
        $affiliateKNPLabs->setActive(true);
        $affiliateKNPLabs->setToken('knp_labs');
        $affiliateKNPLabs->addCategory($manager->merge($this->getReference('category-programming')));
        $affiliateKNPLabs->addCategory($manager->merge($this->getReference('category-design')));

        $affiliateFb = new Affiliate();
        $affiliateFb->setUrl('http://www.facebook.com/');
        $affiliateFb->setEmail('hello@facebook.com');
        $affiliateFb->setActive(true);
        $affiliateFb->addCategory($manager->merge($this->getReference('category-administrator')));
        $affiliateFb->addCategory($manager->merge($this->getReference('category-design')));

        $affiliateYt = new Affiliate();
        $affiliateYt->setUrl('http://www.youtube.com/');
        $affiliateYt->setEmail('hello@youtube.com');
        $affiliateYt->setActive(true);
        $affiliateYt->addCategory($manager->merge($this->getReference('category-administrator')));
        $affiliateYt->addCategory($manager->merge($this->getReference('category-manager')));

        $manager->persist($affiliateSensioLabs);
        $manager->persist($affiliateKNPLabs);
        $manager->persist($affiliateFb);
        $manager->persist($affiliateYt);

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class
        ];
    }
}