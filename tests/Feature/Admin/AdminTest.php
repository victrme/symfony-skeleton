<?php

declare(strict_types=1);

uses(\Symfony\Bundle\FrameworkBundle\Test\WebTestCase::class);
use App\Fixtures\AppFixtures;
use \functional\Admin\AdminTest;
use App\Fixtures\Factory\UserFactory;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

uses(\Zenstruck\Foundry\Test\ResetDatabase::class);

uses(\Zenstruck\Foundry\Test\Factories::class);

beforeEach(function () {
    $this->client = AdminTest::createClient();
    $container = AdminTest::getContainer();
    $this->userRepository = $container->get(UserRepository::class);

    UserFactory::createOne([
        'email' => AppFixtures::ADMIN_EMAIL,
        'password' => '1234',
        'roles' => ['ROLE_ADMIN'],
    ]);
});

test('admin redirects guest', function () {
    $this->client->request('GET', '/admin');

    self::assertResponseRedirects('/login');
});

test('admin accessible by admin', function () {
    $admin = $this->userRepository->findOneBy(['email' => AppFixtures::ADMIN_EMAIL]);

    $this->client->loginUser($admin);
    $this->client->request('GET', '/admin');

    self::assertResponseRedirects();
});
