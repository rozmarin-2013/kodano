<?php

namespace App\Tests\Services;

use App\Services\SendEmailService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendEmailServiceTest extends TestCase
{
    private MailerInterface $mailerMock;
    private SendEmailService $sendEmailService;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->mailerMock = $this->createMock(MailerInterface::class);

        $this->sendEmailService = new SendEmailService($this->mailerMock);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testSendEmail(): void
    {
        $from = 'test@test.com';
        $to = 'recipient@test.com';
        $subject = 'Test Subject';
        $message = '<p>This is a test email message.</p>';

        $this->mailerMock->expects($this->once())
            ->method('send')
            ->with($this->callback(function (Email $email) use ($from, $to, $subject, $message) {
                return $email->getFrom()[0]->getAddress() === $from &&
                    $email->getTo()[0]->getAddress() === $to &&
                    $email->getSubject() === $subject &&
                    $email->getHtmlBody() === $message;
            }));

        $this->sendEmailService->send($message, $from, $to, $subject);
    }

    /**
     * @throws Exception
     */
    public function testSendEmailThrowsException(): void
    {
        $this->expectException(TransportExceptionInterface::class);

        $this->mailerMock->expects($this->once())
            ->method('send')
            ->willThrowException($this->createMock(TransportExceptionInterface::class));

        $this->sendEmailService->send('message', 'from@test.com', 'to@test.com', 'subject');
    }
}
