<?php

namespace api\models;

use common\components\UploadedImage;
use common\models\image\Image;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\models\AbstractApiModel;
use Yii;

class ImageUploadForm extends AbstractApiModel
{
    private const ALLOWED_MIME_TYPES = [
        'image/png',
        'image/jpeg',
    ];

    public function run(): ExecutionResult
    {
        $uploadedImage = UploadedImage::getInstanceByName('image');
        if (!$uploadedImage) {
            return ExecutionResult::exception('Image missing');
        }

        if (!in_array($uploadedImage->getMimeType(), self::ALLOWED_MIME_TYPES)) {
            return ExecutionResult::exception('Disallowed mime type');
        }

        $hash = $uploadedImage->getHash();
        $existingImage = Image::find()->byHash($hash)->one();
        if ($existingImage) {
            return ExecutionResult::success(['id' => $existingImage->getId()]);
        }

        $image = new Image([
            'hash' => $hash,
            'size' => $uploadedImage->getSize(),
            'extension' => $uploadedImage->getExtension()
        ]);
        if (!$image->save()) {
            return ExecutionResult::exception('Cant save image info to a database');
        }

        $filepath = Yii::getAlias("@upload/{$image->constructPath()}");
        $folder = implode('/', explode('/', $filepath, -1));
        if (!file_exists($folder) && !mkdir($folder, 0777, true)) {
            return ExecutionResult::exception('Unable to create a folder to put the image into');
        }
        if (!$uploadedImage->saveAs($filepath)) {
            return ExecutionResult::exception('Cant save image to disk');
        }

        return ExecutionResult::success(['id' => $image->getId()]);
    }
}
