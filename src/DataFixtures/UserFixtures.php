<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('user');
        $user->setEmail('user@email.com');
        $user->setPlainPassword('user');
        $user->addRole('ROLE_USER');
        $user->setEnabled(true);

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@email.com');
        $admin->setPlainPassword('admin');
        $admin->addRole('ROLE_ADMIN');
        $admin->setEnabled(true);

        $manager->persist($user);
        $manager->persist($admin);

        $manager->flush();
    }
}