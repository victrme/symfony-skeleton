<?php

declare(strict_types=1);

uses(\Symfony\Bundle\FrameworkBundle\Test\WebTestCase::class);

test('home', function () {
    $client = self::createClient();
    $client->request('GET', '/');
    self::assertResponseIsSuccessful();
});

test('login', function () {
    $client = self::createClient();
    $client->request('GET', '/login');
    self::assertResponseIsSuccessful();
});

test('register', function () {
    $client = self::createClient();
    $client->request('GET', '/register');
    self::assertResponseIsSuccessful();
});
