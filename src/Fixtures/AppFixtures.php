<?php

namespace App\Fixtures;

use App\Fixtures\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne()
            ->setEmail('admin@example.com')
            ->setRoles(['ROLE_ADMIN']);

        UserFactory::createMany(20);

        $manager->flush();
    }
}
