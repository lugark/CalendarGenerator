<?php

namespace App\Service\Storage\Reader;

interface ReaderInterface
{
    public function readData(string $path, string $type);
}