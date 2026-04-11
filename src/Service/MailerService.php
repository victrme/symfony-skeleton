<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class MailerService
{
    public function __construct(
        private readonly MailerInterface $mailer,
    ) {}

    /**
     * @throws TransportExceptionInterface
     */
    public function send(
        string $subject,
        string $text,
        ?string $to = null,
        ?string $from = null
    ): void {
        $this->mailer->send(
            (new Email())
                ->from($from)
                ->to($to)
                ->subject($subject)
                ->text($text)
        );
    }
}
