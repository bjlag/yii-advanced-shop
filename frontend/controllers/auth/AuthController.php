<?php

namespace frontend\controllers\auth;

use core\forms\auth\LoginForm;
use core\services\auth\LoginService;
use Yii;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Auth controller
 */
class AuthController extends Controller
{
    private $loginService;

    public function __construct(
        string $id,
        Module $module,
        LoginService $loginService,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);

        $this->loginService = $loginService;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
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
     * Logs in a user.
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
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
