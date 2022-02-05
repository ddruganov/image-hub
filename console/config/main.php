<?php

use yii\helpers\ArrayHelper;

$commonConfig = require Yii::getAlias('@common/config/main.php');

return ArrayHelper::merge($commonConfig, [
    'id' => 'app-console',
    'basePath' => Yii::getAlias('@console'),
    'controllerNamespace' => 'console\controllers',
    'params' => require 'params.php',
]);
