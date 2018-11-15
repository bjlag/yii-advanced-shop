<?php

return [
    'class' => yii\web\UrlManager::class,
    'hostInfo' => $params['frontendHostInfo'],
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '' => 'site/index',
        '<_a:about>' => 'site/<_a>',
        '<_a:login|logout>' => 'auth/auth/<_a>',

        'signup' => 'auth/signup/signup',
        'signup/confirm' => 'auth/signup/confirm',

        'login/reset' => 'auth/reset/request',
        'login/reset/confirm' => 'auth/reset/reset',

        '<_c:[\w-]+>' => '<_c>/index',
        '<_c:[\w-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
        '<_c:[\w-]+>/<id:\d+>/<_a:[\w-]+>' => '<_c>/<_a>',
    ],
];