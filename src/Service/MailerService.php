<?php
namespace App\Service;

use App\Security\EmailVerifier;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MailerService{
    public function __construct(private EmailVerifier $emailVerifier, private ParameterBagInterface $params)
    {}

    public function sendVerificationEmailTo($user){
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address($this->params->get('app.platform.email'), 'Support'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('authentication/confirmation_email.html.twig')
        );
    }
}