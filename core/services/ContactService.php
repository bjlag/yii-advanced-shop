<?php

namespace core\services;

use core\forms\ContactForm;
use yii\mail\MailerInterface;

class ContactService
{
    private $mailer;
    private $emailTo;

    /**
     * ContactService constructor.
     * @param string $emailTo
     * @param MailerInterface $mailer
     */
    public function __construct(string $emailTo, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->emailTo = $emailTo;
    }

    /**
     * @param ContactForm $form
     */
    public function send(ContactForm $form): void
    {
        $send = $this->mailer->compose()
            ->setTo($this->emailTo)
            ->setFrom([$form->email => $form->name])
            ->setSubject($form->subject)
            ->setTextBody($form->body)
            ->send();

        if(!$send) {
            throw new \RuntimeException('Во время отправки формы возникла ошибка');
        }
    }
}