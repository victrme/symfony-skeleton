<?php

namespace functional\Auth;

use App\Fixtures\AppFixtures;
use App\Fixtures\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * @internal
 *
 * @coversNothing
 */
class LogoutTest extends WebTestCase
{
    use ResetDatabase;
    use Factories;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        UserFactory::createOne([
            'email' => AppFixtures::ADMIN_EMAIL,
            'password' => '1234',
            'roles' => ['ROLE_ADMIN'],
        ]);
    }

    public function testLogoutClearsSession(): void
    {
        $this->client->loginUser(UserFactory::first());

        $this->client->request('GET', '/');
        self::assertResponseIsSuccessful();

        $this->client->request('GET', '/logout');
        self::assertResponseRedirects();

        $this->client->request('GET', '/admin');
        self::assertResponseRedirects('http://localhost/login');
    }

    public function testLogoutWhenAnonymousRedirects(): void
    {
        $this->client->request('GET', '/logout');
        self::assertResponseRedirects();
    }
}
