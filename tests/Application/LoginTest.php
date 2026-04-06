<?php

namespace App\Tests\Controller;

use App\Fixtures\AppFixtures;
use App\Fixtures\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

/**
 * @internal
 *
 * @coversNothing
 */
class LoginTest extends WebTestCase
{
    use ResetDatabase;
    use Factories;

    private KernelBrowser $client;
    private string $submitText = "";

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $translator = static::getContainer()->get(TranslatorInterface::class);
        $this->submitText = $translator->trans('login.label.submit', domain: 'auth');

        UserFactory::createOne([
            'email' => AppFixtures::ADMIN_EMAIL,
            'password' => '1234',
            'roles' => ['ROLE_ADMIN'],
        ]);

        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();
    }

    public function testInvalidEmail(): void
    {
        $this->client->submitForm($this->submitText, [
            '_username' => 'doesNotExist@example.com',
            '_password' => 'password',
        ]);

        self::assertResponseRedirects('/login');
        $this->client->followRedirect();
        self::assertSelectorExists('.alert-danger');
    }

    public function testInvalidPassword(): void
    {
        $this->client->submitForm($this->submitText, [
            '_username' => AppFixtures::ADMIN_EMAIL,
            '_password' => '0000',
        ]);

        self::assertResponseRedirects('/login');
        $this->client->followRedirect();
        self::assertSelectorExists('.alert-danger');
    }

    public function testValidCredentials(): void
    {
        $this->client->submitForm($this->submitText, [
            '_username' => AppFixtures::ADMIN_EMAIL,
            '_password' => '1234',
        ]);

        self::assertResponseRedirects('/');
        $this->client->followRedirect();
        self::assertSelectorNotExists('.alert-danger');
    }
}
