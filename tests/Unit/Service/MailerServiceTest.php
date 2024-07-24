<?php

use App\Entity\User;
use App\Service\MailerService;
use App\Security\EmailVerifier;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MailerServiceTest extends KernelTestCase{

    private $emailVerifier;

    private $params;

    protected function setUp(): void
    {
        $this->emailVerifier = $this->createMock(EmailVerifier::class);
        $this->params = $this->createMock(ParameterBagInterface::class);
    }

    public function testSendVerificationEmail(){
        $this->params->expects($this->once())
                     ->method('get')
                     ->with('app.platform.email')
                     ->willReturn('no-reply@example.com');

        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('getEmail')
            ->willReturn('user@example.com');

        $templatedEmail = (new TemplatedEmail())
            ->from(new Address('no-reply@example.com', 'Support'))
            ->to('user@example.com')
            ->subject('Please Confirm your Email')
            ->htmlTemplate('authentication/confirmation_email.html.twig');

        $this->emailVerifier->expects($this->once())
                            ->method('sendEmailConfirmation')
                            ->with('app_verify_email', $user, $templatedEmail);

        $mailerService = new MailerService($this->emailVerifier, $this->params);
        $mailerService->sendVerificationEmailTo($user);
    }
}