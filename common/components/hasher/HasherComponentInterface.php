<?php

namespace common\components\hasher;

interface HasherComponentInterface
{
    public function string(string $value): string;
    public function file(string $filepath): string;
}
