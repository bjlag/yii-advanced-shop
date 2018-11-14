<?php

namespace frontend\controllers;

use core\forms\ContactForm;
use core\forms\auth\LoginForm;
use core\forms\auth\PasswordResetRequestForm;
use core\forms\auth\ResetPasswordForm;
use core\forms\auth\SignupForm;
use core\services\auth\LoginService;
use core\services\auth\PasswordResetService;
use core\services\auth\SignupService;
use core\services\ContactService;
use Yii;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    private $loginService;
    private $signupService;
    private $passwordResetService;
    private $contactService;

    public function __construct(
        string $id,
        Module $module,
        LoginService $loginService,
        SignupService $signupService,
        PasswordResetService $passwordResetService,
        ContactService $contactService,
        array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->loginService = $loginService;
        $this->signupService = $signupService;
        $this->passwordResetService = $passwordResetService;
        $this->contactService = $contactService;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
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
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if ($this->loginService->login($form)) {
                return $this->goBack();
            }

            Yii::$app->session->setFlash('error', 'Неверный логин или пароль');
        }

        $form->password = '';

        return $this->render('login', [
            'model' => $form,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
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

        return $this->render('contact', [
            'model' => $form,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $form = new SignupForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            if ($user = $this->signupService->request($form)) {
                Yii::$app->session->setFlash('success', 'Для подтверждения емейла проверьте почту и следуйте инструкциям в письме.');
                return $this->goHome();
            }
        }

        return $this->render('signup', [
            'model' => $form,
        ]);
    }

    /**
     * Подтверждение адреса электронной почты.
     * @param string $token
     * @return \yii\web\Response
     */
    public function actionConfirmEmail(string $token)
    {
        try {
            $user = $this->signupService->confirm($token);
            Yii::$app->getUser()->login($user);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('success', $e->getMessage());
        }

        return $this->goHome();
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $form = new PasswordResetRequestForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->passwordResetService->request($form);
                Yii::$app->session->setFlash('success', 'Проверьте почту и следуйте инструкциям в письме.');
                return $this->goHome();
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $form,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     */
    public function actionResetPassword($token)
    {
        $form = new ResetPasswordForm();

        try {
            $user = $this->passwordResetService->validateToken($token);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());

            return $this->goHome();
        }

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->passwordResetService->reset($user, $form->password);
                Yii::$app->session->setFlash('success', 'Новый пароль сохранен');

                return $this->goHome();

            } catch (\RuntimeException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('resetPassword', [
            'model' => $form,
        ]);
    }
}
