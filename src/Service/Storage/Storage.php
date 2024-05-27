<?php

namespace App\Service\Storage;

use App\Service\Storage\Reader\ReaderInterface;
use App\Service\Storage\Writer\WriterInterface;
use MessagePack\MessagePack;

class Storage
{
    public const STORAGE_TYPE_PUBLIC_HOLIDAY = 'publicHolidays';
    public const STORAGE_TYPE_SCHOOL_HOLIDAY = 'schoolHolidays';

    protected string $dataPath;

    public function __construct(
        private readonly WriterInterface $writer, 
        private readonly ReaderInterface $reader
    ) {
    }

    protected function getDataPath(): string
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

    
    /**
     *  @return array<mixed>
     */
    public function readPublicHolidays(string $federal): array
    {
        return array_filter(
            $this->reader->readData($this->getDataPath(), self::STORAGE_TYPE_PUBLIC_HOLIDAY),
            fn($holiday) => in_array($federal, $holiday['holiday']['regions'])
        );
    }

    /**
     *  @return array<mixed>
     */
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

    /**
     *  @param array<mixed> $data
     */
    public function writePublicHolidays(array $data): void
    {
        $this->writer->writeData($this->getDataPath(), self::STORAGE_TYPE_PUBLIC_HOLIDAY, $data);
    }

    /**
     *  @param array<mixed> $data
     */
    public function writeSchoolHolidays(array $data): void
    {
        $this->writer->writeData($this->getDataPath(), self::STORAGE_TYPE_SCHOOL_HOLIDAY, $data);
    }

}