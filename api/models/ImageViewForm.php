<?php

namespace api\models;

use common\models\image\Image;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\models\AbstractApiModel;
use Yii;

class ImageViewForm extends AbstractApiModel
{
    public int $id;
    public ?int $width = null;
    public ?int $height = null;

    public function run(): ExecutionResult
    {
        $image = Image::findOne($this->id);
        if (!$image) {
            return ExecutionResult::exception('Image doesnt exist');
        }

        $modifiers = $this->getModifiers();
        $filepath = $modifiers
            ? $this->modifyImage($image)
            : Yii::getAlias("@upload/{$image->constructPath()}");

        if (!$filepath) {
            return ExecutionResult::exception('Image not found');
        }

        return ExecutionResult::success([
            'filepath' => $filepath
        ]);
    }

    private function modifyImage(Image $image)
    {
        return null;
    }

    private function getModifiers()
    {
        return array_filter($this->getAttributes(null, ['id']));
    }
}
