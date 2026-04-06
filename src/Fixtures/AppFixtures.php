<?php

namespace App\Fixtures;

use App\Fixtures\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public const ADMIN_EMAIL = 'admin@example.com';

    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne()
            ->setEmail(self::ADMIN_EMAIL)
            ->setRoles(['ROLE_ADMIN'])
        ;

        UserFactory::createMany(20);

        $manager->flush();
    }
}
