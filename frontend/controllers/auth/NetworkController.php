<?php

namespace frontend\controllers\auth;

use core\services\auth\NetworkService;
use yii\authclient\ClientInterface;
use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Network controller
 */
class NetworkController extends Controller
{
    private $service;

    /**
     * NetworkController constructor.
     * @param string $id
     * @param Module $module
     * @param NetworkService $networkService
     * @param array $config
     */
    public function __construct(string $id, Module $module, NetworkService $networkService, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->service = $networkService;
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    /**
     * @param ClientInterface $client
     */
    public function onAuthSuccess(ClientInterface $client)
    {
        $network = $client->getId();
        $attributes = $client->getUserAttributes();
        $identity = ArrayHelper::getValue($attributes, 'id');

        try {
            $user = $this->service->auth($network, $identity);
            \Yii::$app->user->login($user, \Yii::$app->params['user.passwordResetTokenExpire']);
        } catch (\DomainException $e) {
            \Yii::$app->errorHandler->logException($e);
            \Yii::$app->session->setFlash('error', $e->getMessage());
        }
    }
}
