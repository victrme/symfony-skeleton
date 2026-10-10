<?php

namespace App\Fixtures\Factory;

use App\Entity\Message;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Message>
 */
final class MessageFactory extends PersistentObjectFactory
{
    public static function class(): string
    {
        return Message::class;
    }

    protected function defaults(): array
    {
        return [
            'sender' => UserFactory::random(),
            'recipient' => UserFactory::random(),
            'subject' => self::faker()->sentence(),
            'body' => self::faker()->paragraphs(4, true),
            'createdAt' => $this->randomDate(),
            'readAt' => self::faker()->boolean(70) ? $this->randomDate() : null,
        ];
    }

    private function randomDate(): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromInterface(
            self::faker()->dateTimeBetween('-6 months', 'now')
        );
    }
}
