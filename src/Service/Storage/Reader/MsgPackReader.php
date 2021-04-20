<?php

namespace App\Service\Storage\Reader;

use MessagePack\MessagePack;

class MsgPackReader implements ReaderInterface
{
    public function readData(string $path, string $type)
    {
        return MessagePack::unpack(file_get_contents($path . '/' .$type . '.mpack'));
    }
}