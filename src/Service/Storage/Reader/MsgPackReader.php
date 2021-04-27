<?php

namespace App\Service\Storage\Reader;

use App\Service\Storage\Writer\MsgPackWriter;
use MessagePack\MessagePack;

class MsgPackReader implements ReaderInterface
{
    public function readData(string $path, string $type)
    {
        return MessagePack::unpack(file_get_contents($path . '/' .$type . MsgPackWriter::FILE_ENDING));
    }
}