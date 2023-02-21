<?php
namespace App\Mail;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;

class MailService
{
    private $mailer;
    
    public function __construct(MailerInterface $mailer){
        $this->mailer = $mailer;
    }

    public function sendmail($to, $content,$subject,$text) : void
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->text($text)
            //->html('<p>See Twig integration for better HTML integration!</p>')
            ;

        $mailer->send($email);
    }
}