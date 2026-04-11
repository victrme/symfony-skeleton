<?php

use App\Fixtures\AppFixtures;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * @internal
 *
 * @coversNothing
 */
final class AppFixturesTest extends KernelTestCase
{
    use ResetDatabase;
    use Factories;

    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $container = self::getContainer();
        $this->userRepository = $container->get(UserRepository::class);
    }

    public function testLoadsAdminUser(): void
    {
        $fixtures = self::getContainer()->get(AppFixtures::class);
        $fixtures->load(self::getContainer()->get('doctrine')->getManager());

        $admin = $this->userRepository->findOneBy(['email' => AppFixtures::ADMIN_EMAIL]);

        self::assertNotNull($admin);
        self::assertContains('ROLE_ADMIN', $admin->getRoles());
    }

    public function testLoadsExpectedUserCount(): void
    {
        $fixtures = self::getContainer()->get(AppFixtures::class);
        $fixtures->load(self::getContainer()->get('doctrine')->getManager());

        // 1 admin + 20 random users
        self::assertCount(21, $this->userRepository->findAll());
    }
}
