<?php

namespace App\Tests\Notification;

use App\Message\EmailNotification;
use App\Notification\NotificationEmail;
use App\Notification\NotificationInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Envelope;

class NotificationEmailTest extends TestCase
{
    /**
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function testNotificationEmailDispatchesMessage()
    {
        $busMock = $this->createMock(MessageBusInterface::class);

        $busMock->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function (EmailNotification $notification) {
                return $notification->getContent() === 'test message!';
            }))
            ->willReturn(new Envelope(new EmailNotification('test message!')));

        $notificationService = new NotificationEmail($busMock);

        $notificationService->send('test message!');
    }
}
