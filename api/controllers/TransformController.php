<?php

namespace api\controllers;

use api\models\ImageTransformForm;
use ddruganov\Yii2ApiEssentials\http\actions\ApiAction;
use ddruganov\Yii2ApiEssentials\http\actions\ClosureAction;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class TransformController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verb' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index'  => ['GET']
                ],
            ]
        ]);
    }

    public function actions()
    {
        return [
            'index' => [
                'class' => ClosureAction::class,
                'closure' => function (ApiAction $apiAction) {
                    $imageTransformForm = new ImageTransformForm($apiAction->getData());
                    $result = $imageTransformForm->run();
                    if (!$result->isSuccessful()) {
                        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
                        return $result;
                    }
                    Yii::$app->getResponse()->sendFile($result->getData('filepath'));
                    Yii::$app->end();
                }
            ]
        ];
    }
}
