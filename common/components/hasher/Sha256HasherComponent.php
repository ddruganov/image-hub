<?php

namespace common\components\hasher;

final class Sha256HasherComponent implements HasherComponentInterface
{
    public function string(string $value): string
    {
        return hash('sha256', $value);
    }

    public function file(string $filepath): string
    {
        return hash_file('sha256', $filepath);
    }
}
