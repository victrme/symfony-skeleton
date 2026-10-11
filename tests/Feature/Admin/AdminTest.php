<?php

declare(strict_types=1);

namespace App\Tests\Feature\Admin;

use App\Factory\UserFactory;
use App\Fixtures\AppFixtures;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class AdminTest extends WebTestCase
{
    use Factories;
    use ResetDatabase;

    private KernelBrowser $client;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = self::createClient();
        $container = self::getContainer();
        $this->userRepository = $container->get(UserRepository::class);

        UserFactory::createOne([
            'email' => AppFixtures::ADMIN_EMAIL,
            'password' => '1234',
            'roles' => ['ROLE_ADMIN'],
        ]);
    }

    public function testAdminRedirectsGuest(): void
    {
        $this->client->request('GET', '/admin');

        self::assertResponseRedirects('/login');
    }

    public function testAdminAccessibleByAdmin(): void
    {
        $admin = $this->userRepository->findOneBy(['email' => AppFixtures::ADMIN_EMAIL]);

        $this->client->loginUser($admin);
        $this->client->request('GET', '/admin');

        self::assertResponseRedirects();
    }
}
