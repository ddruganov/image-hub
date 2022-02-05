<?php

use yii\helpers\ArrayHelper;

$commonConfig = require Yii::getAlias('@common/config/main.php');

return ArrayHelper::merge($commonConfig, [
    'id' => 'app-api',
    'basePath' => Yii::getAlias('@api'),
    'controllerNamespace' => 'api\controllers',
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => require 'routes.php'
        ],
    ],
    'params' => require 'params.php',
]);
