<?php

namespace App\Tests\Controller;

use App\Fixtures\AppFixtures;
use App\Fixtures\Factory\UserFactory;
use App\Repository\UserRepository;
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
class RegisterTest extends WebTestCase
{
    use ResetDatabase;
    use Factories;

    private KernelBrowser $client;
    private UserRepository $userRepository;
    private string $submitText = '';
    private string $titleText = '';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();
        $this->userRepository = $container->get(UserRepository::class);

        $translator = $container->get(TranslatorInterface::class);
        $this->submitText = $translator->trans('register.label.submit', domain: 'auth');
        $this->titleText = $translator->trans('register.title', domain: 'auth');

        UserFactory::createOne([
            'email' => AppFixtures::ADMIN_EMAIL,
            'password' => '1234',
            'roles' => ['ROLE_ADMIN'],
        ]);

        $this->client->request('GET', '/register');
        self::assertResponseIsSuccessful();
        self::assertPageTitleContains($this->titleText);
    }

    public function testRegister(): void
    {
        $this->client->submitForm($this->submitText, [
            'register_form[email]' => 'me@example.com',
            'register_form[plainPassword]' => 'password',
            'register_form[agreeTerms]' => true,
        ]);

        self::assertNotNull($this->userRepository->findOneBy([
            'email' => 'me@example.com'
        ]));
    }

    public function testRegisterWithExistingEmail(): void
    {
        $this->client->submitForm($this->submitText, [
            'register_form[email]' => AppFixtures::ADMIN_EMAIL,
            'register_form[plainPassword]' => '1234',
            'register_form[agreeTerms]' => true,
        ]);

        self::assertResponseIsUnprocessable();
        self::assertCount(1, $this->userRepository->findAll());
    }
}
