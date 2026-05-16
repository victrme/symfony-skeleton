<?php

declare(strict_types=1);

uses(\Symfony\Bundle\FrameworkBundle\Test\WebTestCase::class);
use App\Fixtures\AppFixtures;
use App\Fixtures\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Contracts\Translation\TranslatorInterface;

uses(\Zenstruck\Foundry\Test\ResetDatabase::class);

uses(\Zenstruck\Foundry\Test\Factories::class);

beforeEach(function () {
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
});

test('invalid email', function () {
    $this->client->submitForm($this->submitText, [
        '_username' => 'doesNotExist@example.com',
        '_password' => 'password',
    ]);

    self::assertResponseRedirects('/login');
    $this->client->followRedirect();
    self::assertSelectorExists('.alert-danger');
});

test('invalid password', function () {
    $this->client->submitForm($this->submitText, [
        '_username' => AppFixtures::ADMIN_EMAIL,
        '_password' => '0000',
    ]);

    self::assertResponseRedirects('/login');
    $this->client->followRedirect();
    self::assertSelectorExists('.alert-danger');
});

test('valid credentials', function () {
    $this->client->submitForm($this->submitText, [
        '_username' => AppFixtures::ADMIN_EMAIL,
        '_password' => '1234',
    ]);

    self::assertResponseRedirects('/');
    $this->client->followRedirect();
    self::assertSelectorNotExists('.alert-danger');
});
