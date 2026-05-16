<?php

declare(strict_types=1);

uses(\Symfony\Bundle\FrameworkBundle\Test\WebTestCase::class);
use App\Fixtures\AppFixtures;
use App\Fixtures\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

uses(\Zenstruck\Foundry\Test\ResetDatabase::class);

uses(\Zenstruck\Foundry\Test\Factories::class);

beforeEach(function () {
    $this->client = static::createClient();

    UserFactory::createOne([
        'email' => AppFixtures::ADMIN_EMAIL,
        'password' => '1234',
        'roles' => ['ROLE_ADMIN'],
    ]);
});

test('logout clears session', function () {
    $this->client->loginUser(UserFactory::first());

    $this->client->request('GET', '/');
    self::assertResponseIsSuccessful();

    $this->client->request('GET', '/logout');
    self::assertResponseRedirects();

    $this->client->request('GET', '/admin');
    self::assertResponseRedirects('http://localhost/login');
});

test('logout when anonymous redirects', function () {
    $this->client->request('GET', '/logout');
    self::assertResponseRedirects();
});
