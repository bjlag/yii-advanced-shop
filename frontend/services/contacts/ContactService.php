<?php

namespace frontend\services\contacts;

use frontend\forms\ContactForm;
use Yii;

class ContactService
{
    public function send(ContactForm $form, string $email): void
    {
        $send = Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([$form->email => $form->name])
            ->setSubject($form->subject)
            ->setTextBody($form->body)
            ->send();

        if(!$send) {
            throw new \RuntimeException('Во время отправки формы возникла ошибка');
        }
    }
}