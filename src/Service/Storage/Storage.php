<?php

namespace App\Service\Storage;

use App\Service\Storage\Reader\ReaderInterface;
use App\Service\Storage\Writer\WriterInterface;
use MessagePack\MessagePack;

class Storage
{
    const STORAGE_TYPE_PUBLIC_HOLIDAY = 'publicHolidays';
    const STORAGE_TYPE_SCHOOL_HOLIDAY = 'schoolHolidays';

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

    public function setDataPath(string $dataPath)
    {
        $this->dataPath = realpath($dataPath);
        if ($this->dataPath === false) {
            throw new \Exception('could not read path: ' . $dataPath);
        }
    }

    public function readPublicHolidays(string $federal): array
    {
        return array_filter(
            $this->reader->readData($this->getDataPath(), self::STORAGE_TYPE_PUBLIC_HOLIDAY),
            function($holiday) use ($federal) {
                return in_array($federal, $holiday['holiday']['regions']);
            }
        );
    }

    public function readSchoolHolidays(string $federal): array
    {
        $filteredData = array_filter(
            $this->reader->readData($this->getDataPath(), self::STORAGE_TYPE_SCHOOL_HOLIDAY),
            function($vacation) use ($federal) {
                return array_key_exists($federal, $vacation) && !empty($vacation[$federal]);
            }
        );

        return array_map(
            function($vacation) use ($federal) {
                return [
                    'name' => $vacation['name'],
                    'start' => $vacation[$federal]['start'],
                    'end' => $vacation[$federal]['end'],
                ];
            },
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