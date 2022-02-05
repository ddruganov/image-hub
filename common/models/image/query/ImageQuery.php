<?php

namespace common\models\image\query;

use common\models\image\Image;
use yii\db\ActiveQuery;

final class ImageQuery extends ActiveQuery
{
    public function __construct()
    {
        parent::__construct(Image::class);
        $this->alias('image');
    }

    public function byHash(string $value)
    {
        return $this
            ->andWhere([
                'image.hash' => $value
            ]);
    }
}
