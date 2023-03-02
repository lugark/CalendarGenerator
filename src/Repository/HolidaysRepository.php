<?php

namespace App\Repository;

use App\Serializer\Normalizer\EventNormalizer;
use App\Service\Storage\Storage;
use Calendar\Pdf\Renderer\Event\Event;
use Calendar\Pdf\Renderer\Event\Types;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;

class HolidaysRepository
{
    /** @var Storage */
    private $storage;

    /** @var Serializer  */
    private $serializer;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
        $this->serializer = new Serializer(
            [
                new EventNormalizer(),
            ]
        );
    }

    public function getPublicHolidays(string $federal): array
    {
        $filteredHolidays = $this->storage->readPublicHolidays($federal);
        $holidays = [];
        foreach ($filteredHolidays as $data) {
            $holidays[] = $this->serializer->denormalize(
                $data['holiday'],
                Event::class,
                null,
                ['eventType' => Types::EVENT_TYPE_PUBLIC_HOLIDAY]
            );
        }
        return $holidays;
    }

    public function getSchoolHolidays(string $federal): array
    {
        $filteredHolidays = $this->storage->readSchoolHolidays($federal);
        $holidays = [];
        foreach ($filteredHolidays as $data) {
            $holidays[] = $this->serializer->denormalize(
                $data,
                Event::class,
                null,
                ['eventType' => Types::EVENT_TYPE_SCHOOL_HOLIDAY]
            );
        }
        return $holidays;
    }

    public function savePublicHolidays(array $data):void
    {
        $this->storage->writePublicHolidays($data);
    }

    public function saveSchoolHolidays(array $data):void
    {
        $this->storage->writeSchoolHolidays($data);
    }
}
