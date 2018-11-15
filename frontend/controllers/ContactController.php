<?php

namespace frontend\controllers;

use core\forms\ContactForm;
use core\services\ContactService;
use Yii;
use yii\base\Module;
use yii\web\Controller;

/**
 * Contact controller
 */
class ContactController extends Controller
{
    private $contactService;

    public function __construct(
        string $id,
        Module $module,
        ContactService $contactService,
        array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->contactService = $contactService;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays contact page.
     * @return mixed
     */
    public function actionIndex()
    {
        $form = new ContactForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->contactService->send($form);
                Yii::$app->session->setFlash('success', 'Сообщение отправлено! Скоро с вами свяжутся наши менеджеры.');

                return $this->refresh();

            } catch (\Exception $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('index', [
            'model' => $form,
        ]);
    }
}
