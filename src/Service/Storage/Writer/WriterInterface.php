<?php

namespace App\Service\Storage\Writer;

interface WriterInterface
{
    public function writeData(string $path, string $type, mixed $data): bool;
}