<?php

namespace App\Service\Storage;

use App\Service\Storage\Reader\ReaderInterface;
use App\Service\Storage\Writer\WriterInterface;
use MessagePack\MessagePack;

class Storage
{
    public const STORAGE_TYPE_PUBLIC_HOLIDAY = 'publicHolidays';
    public const STORAGE_TYPE_SCHOOL_HOLIDAY = 'schoolHolidays';

    protected $dataPath;

    /** @var WriterInterface */
    protected $writer;

    /** @var ReaderInterface */
    protected $reader;

    public function __construct(WriterInterface $writer, ReaderInterface $reader)
    {
        $this->writer = $writer;
        $this->reader = $reader;
    }

    protected function getDataPath()
    {
        return $this->dataPath;
    }

    public function setDataPath(string $dataPath): void
    {
        $this->dataPath = realpath($dataPath);
        if ($this->dataPath === false) {
            throw new StorageException('could not read path: ' . $dataPath);
        }
    }

    public function readPublicHolidays(string $federal): array
    {
        return array_filter(
            $this->reader->readData($this->getDataPath(), self::STORAGE_TYPE_PUBLIC_HOLIDAY),
            fn($holiday) => in_array($federal, $holiday['holiday']['regions'])
        );
    }

    public function readSchoolHolidays(string $federal): array
    {
        $filteredData = array_filter(
            $this->reader->readData($this->getDataPath(), self::STORAGE_TYPE_SCHOOL_HOLIDAY),
            fn($vacation) => array_key_exists($federal, $vacation) && !empty($vacation[$federal])
        );

        return array_map(
            fn($vacation) => [
                'name' => $vacation['name'],
                'start' => $vacation[$federal]['start'],
                'end' => $vacation[$federal]['end'],
            ],
            $filteredData
        );
    }

    public function writePublicHolidays(array $data):void
    {
        $this->writer->writeData($this->getDataPath(), self::STORAGE_TYPE_PUBLIC_HOLIDAY, $data);
    }

    public function writeSchoolHolidays(array $data):void
    {
        $this->writer->writeData($this->getDataPath(), self::STORAGE_TYPE_SCHOOL_HOLIDAY, $data);
    }

}