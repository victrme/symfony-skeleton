<?php

declare(strict_types=1);

namespace App\Tests\Feature\Auth;

use App\Factory\UserFactory;
use App\Fixtures\AppFixtures;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class LoginTest extends WebTestCase
{
    use Factories;
    use ResetDatabase;

    private KernelBrowser $client;
    private string $submitText;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();

        $translator = static::getContainer()->get(TranslatorInterface::class);
        $this->submitText = $translator->trans('login.label.submit');

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
