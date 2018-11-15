<?php

namespace frontend\controllers\auth;

use core\forms\auth\PasswordResetRequestForm;
use core\forms\auth\ResetPasswordForm;
use core\services\auth\PasswordResetService;
use Yii;
use yii\base\Module;
use yii\web\Controller;

/**
 * Reset controller
 */
class ResetController extends Controller
{
    private $passwordResetService;

    public function __construct(
        string $id,
        Module $module,
        PasswordResetService $passwordResetService,
        array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->passwordResetService = $passwordResetService;
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequest()
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

        return $this->render('request', [
            'model' => $form,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     */
    public function actionReset($token)
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

        return $this->render('reset', [
            'model' => $form,
        ]);
    }
}
