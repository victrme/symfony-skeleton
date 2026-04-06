<?php

namespace App\Tests\Controller;

use App\Fixtures\AppFixtures;
use App\Fixtures\Factory\UserFactory;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * @internal
 *
 * @coversNothing
 */
final class AdminTest extends WebTestCase
{
    use ResetDatabase;
    use Factories;

    private KernelBrowser $client;
    private UserRepository $userRepository;
    private string $submitText = '';
    private string $titleText = '';

    protected function setUp(): void
    {
        $this->client = AdminTest::createClient();
        $container = AdminTest::getContainer();
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
