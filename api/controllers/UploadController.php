<?php

namespace api\controllers;

use api\models\ImageUploadForm;
use ddruganov\Yii2ApiEssentials\http\actions\ApiModelAction;
use ddruganov\Yii2ApiEssentials\http\controllers\ApiController;
use yii\filters\VerbFilter;

class UploadController extends ApiController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verb' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index'  => ['POST']
                ],
            ]
        ]);
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => ApiModelAction::class,
                'modelClass' => ImageUploadForm::class
            ]
        ];
    }
}
