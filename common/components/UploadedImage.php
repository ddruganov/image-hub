<?php

namespace common\components;

use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

final class UploadedImage extends UploadedFile
{
    public function getTmpName()
    {
        return $this->tempName;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getMimeType()
    {
        return strtolower(FileHelper::getMimeType($this->getTmpName()));
    }

    public function getExtension()
    {
        $extension = parent::getExtension();
        if ($extension) {
            return $extension;
        }

        $extensions = FileHelper::getExtensionsByMimeType($this->getMimeType());
        return @reset($extensions) ?: 'jpg';
    }

    public function getHash()
    {
        return Yii::$app->get('hasher')->hashFile($this->getTmpName());
    }

    public static function getInstanceByName($name): static
    {
        return parent::getInstanceByName($name);
    }
}
