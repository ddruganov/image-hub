<?php

namespace api\models;

use common\models\image\Image;
use ddruganov\Yii2ApiEssentials\ExecutionResult;
use ddruganov\Yii2ApiEssentials\models\AbstractApiModel;
use Imagick;
use Yii;

class ImageTransformForm extends AbstractApiModel
{
    public int $id;
    public ?int $width = null;
    public ?int $height = null;
    public ?int $quality = null;
    public ?string $crop = null;

    public function run(): ExecutionResult
    {
        $image = Image::findOne($this->id);
        if (!$image) {
            return ExecutionResult::exception('Image doesnt exist');
        }

        $originalFilepath = Yii::getAlias("@upload/{$image->constructPath()}");
        [$transformedFolder, $transformedFilepath] = $this->getTransformedFilepath($originalFilepath);
        if (file_exists($transformedFilepath)) {
            return ExecutionResult::success(['filepath' => $transformedFilepath]);
        }
        if (!file_exists($transformedFolder) && !mkdir($transformedFolder, 0777, true)) {
            return ExecutionResult::exception('Unable to create a folder to put the transformed image into');
        }

        $imagick = $this->modifyImage($originalFilepath);
        if (!$imagick->writeImage($transformedFilepath)) {
            return ExecutionResult::exception('Cant save image to disk');
        }

        return ExecutionResult::success([
            'filepath' => $transformedFilepath
        ]);
    }

    private function getTransformedFilepath(string $originalFilepath)
    {
        $extension = strtolower(pathinfo($originalFilepath, PATHINFO_EXTENSION));
        $hash = Yii::$app->get('hasher')->hash($this->getAttributes());
        $folder = Yii::getAlias(join('/', ['@transformed', substr($hash, 0, 2), substr($hash, 2, 2)]));
        return [$folder, "$folder/$hash.$extension"];
    }

    private function modifyImage(string $filepathToLoadFrom)
    {
        $imagick = new Imagick($filepathToLoadFrom);

        $this->bootstrapModifiers($imagick);

        $this->cropImage($imagick);
        $imagick->setImageCompressionQuality($this->quality);

        return $imagick;
    }

    private function bootstrapModifiers(Imagick $imagick)
    {
        $this->width ??= $imagick->getImageWidth();
        if ($this->width <= 0) {
            $this->width = 1;
        }
        if ($this->width > $imagick->getImageWidth()) {
            $this->width = $imagick->getImageWidth();
        }

        $this->height ??= $imagick->getImageHeight();
        if ($this->height <= 0) {
            $this->height = 1;
        }
        if ($this->height > $imagick->getImageHeight()) {
            $this->height = $imagick->getImageHeight();
        }

        $this->quality ??= 100;
        if ($this->quality <= 0) {
            $this->quality = 1;
        }
        if ($this->quality > 100) {
            $this->quality = 100;
        }

        if (!in_array($this->crop, ['thumbnail', 'center', 'fit'])) {
            $this->crop = 'fit';
        }
    }

    private function cropImage(Imagick $imagick)
    {
        if ($this->crop === 'thumbnail') {
            $imagick->cropThumbnailImage($this->width, $this->height);
            return;
        }

        if ($this->crop === 'center') {
            $x = ceil(($imagick->getImageWidth() - $this->width) / 2);
            $y = ceil(($imagick->getImageHeight() - $this->height) / 2);
            $imagick->cropImage($this->width, $this->height, $x, $y);
            return;
        }

        if ($this->crop === 'fit') {
            $imagick->scaleImage($this->width, $this->height, true);
            return;
        }
    }
}
