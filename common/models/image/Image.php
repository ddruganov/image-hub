<?php

namespace common\models\image;

use common\models\image\query\ImageQuery;
use ddruganov\Yii2ApiEssentials\behaviors\TimestampBehavior;
use ddruganov\Yii2ApiEssentials\DateHelper;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $size
 * @property string $hash
 * @property string $extension
 * @property string $created_at
 */
final class Image extends ActiveRecord
{
    public static function tableName()
    {
        return 'image.image';
    }

    public static function find(): ImageQuery
    {
        return new ImageQuery();
    }

    public function rules()
    {
        return [
            [['size', 'hash', 'extension', 'created_at'], 'required'],
            [['size'], 'integer'],
            [['hash', 'extension', 'created_at'], 'string'],
            [['created_at'], 'date', 'format' => 'php:Y-m-d H:i:s']
        ];
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function constructPath()
    {
        $date = DateHelper::changeFormat($this->getCreatedAt(), '!Y-m-d H:i:s', 'Y/m/d');
        return "$date/{$this->getId()}.{$this->getExtension()}";
    }
}
