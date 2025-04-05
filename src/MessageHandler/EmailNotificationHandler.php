<?php

namespace App\MessageHandler;

use App\Message\EmailNotification;
use App\Services\SendEmailService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class EmailNotificationHandler
{
    public function __construct(
        private readonly SendEmailService $sendEmailService,
        private readonly LoggerInterface  $logger,
        private readonly string $emailFrom,
        private readonly string $emailTo
    ){}

    public function __invoke(EmailNotification $message)
    {
        $this->logger->info($this->emailFrom . ' ' . $this->emailTo);
        $this->logger->info('send email');
        $this->sendEmailService->send($message->getContent(), $this->emailFrom, $this->emailTo, 'message');
    }
}