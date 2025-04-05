<?php

namespace App\Notification;

use App\Message\EmailNotification;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class NotificationEmail implements NotificationInterface
{
    public function __construct(private MessageBusInterface $bus)
    {}

    /**
     * @throws ExceptionInterface
     */
    public function send(string $message): void
    {
        $this->bus->dispatch(new EmailNotification($message));
    }
}