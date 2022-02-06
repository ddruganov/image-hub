<?php

namespace common\components\hasher;

interface HasherComponentInterface
{
    public function hash(mixed $value): string;
    public function hashFile(string $filepath): string;
}
