<?php

namespace common\components\hasher;

use yii\helpers\Json;

final class Sha256HasherComponent implements HasherComponentInterface
{
    public function hash(mixed $value): string
    {
        return hash('sha256', Json::encode($value));
    }

    public function hashFile(string $filepath): string
    {
        return hash_file('sha256', $filepath);
    }
}
