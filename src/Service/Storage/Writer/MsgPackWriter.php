<?php

namespace App\Service\Storage\Writer;

use MessagePack\MessagePack;

class MsgPackWriter implements WriterInterface
{
    const FILE_ENDING = '.mpack';

    public function writeData(string $path, string $type, $data): bool
    {
        $dataFile = $path . '/' . $type . self::FILE_ENDING;
        $f = fopen($dataFile, 'w+b');
        if (!empty($data)) {
            $packedData = MessagePack::pack($data);
            fwrite($f, $packedData, strlen($packedData));
        }
        fclose($f);
        return true;
    }

}