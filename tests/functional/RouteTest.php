<?php

declare(strict_types=1);

namespace functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class RouteTest extends WebTestCase
{
    public function testHome(): void
    {
        $client = self::createClient();
        $client->request('GET', '/');
        self::assertResponseIsSuccessful();
    }

    public function testLogin(): void
    {
        $client = self::createClient();
        $client->request('GET', '/login');
        self::assertResponseIsSuccessful();
    }

    public function testRegister(): void
    {
        $client = self::createClient();
        $client->request('GET', '/register');
        self::assertResponseIsSuccessful();
    }
}
