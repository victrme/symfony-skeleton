<?php

declare(strict_types=1);

use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class MailTest extends KernelTestCase
{
    private MailerService $mailer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mailer = self::getContainer()->get(MailerService::class);
    }

    public function testMailSendsEmail(): void
    {
        $this->mailer->send(
            subject: "Hello world !",
            text: "This is a test mail.",
            to: "client@domain.com",
            from: "noreply@example.com"
        );

        $this->assertEmailCount(1);
        $email = $this->getMailerMessage();

        $this->assertEmailSubjectContains($email, 'Hello world !');
        $this->assertEmailTextBodyContains($email, 'This is a test mail.');
        $this->assertEmailAddressContains($email, 'from', 'noreply@example.com');
        $this->assertEmailAddressContains($email, 'to', 'client@domain.com');
    }
}
