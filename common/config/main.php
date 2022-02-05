<?php

use common\components\hasher\Sha256HasherComponent;
use yii\db\Connection;
use yii\log\FileTarget;
use yii\redis\Cache;

return [
    'vendorPath' => Yii::getAlias('@vendor'),
    'bootstrap' => ['log'],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'categories' => ['application'],
                    'logVars' => [],
                    'logFile' => '@logs/main.log',
                    'enableRotation' => false,
                    'prefix' => fn () => ''
                ]
            ],
        ],
        'db' => [
            'class' => Connection::class,
            'dsn' => 'pgsql:host=image-hub-db;dbname=imagehub',
            'username' => 'imagehub',
            'password' => 'imagehub',
            'charset' => 'utf8',
            'enableSchemaCache' => false,
        ],
        'cache' => [
            'class' => Cache::class,
            'redis' => [
                'hostname' => 'image-hub-redis',
                'port' => 6379,
                'database' => 0,
            ]
        ],
        'hasher' => Sha256HasherComponent::class
    ],
    'params' => require 'params.php',
];
