<?php

namespace frontend\controllers\auth;

use core\forms\auth\SignupForm;
use core\services\auth\SignupService;
use Yii;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Signup controller
 */
class SignupController extends Controller
{
    private $signupService;

    public function __construct(
        string $id,
        Module $module,
        SignupService $signupService,
        array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->signupService = $signupService;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
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
    public function actionConfirm(string $token)
    {
        try {
            $user = $this->signupService->confirm($token);
            Yii::$app->getUser()->login($user);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->goHome();
    }
}
