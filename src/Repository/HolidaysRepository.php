<?php

namespace App\Repository;

use App\Service\Storage\Storage;
use Calendar\Pdf\Renderer\Event\Event;
use Calendar\Pdf\Renderer\Event\Types;

class HolidaysRepository
{
    public function __construct(
        private readonly Storage $storage
    ) {
    }

    /**
     * @return array<mixed>
     */
    public function getPublicHolidays(string $federal): array
    {
        $filteredHolidays = $this->storage->readPublicHolidays($federal);
        $holidays = [];
        foreach ($filteredHolidays as $data) {
            $holidays[] = Event::fromArray($data['holiday'], Types::EVENT_TYPE_PUBLIC_HOLIDAY);
        }
        return $holidays;
    }

    /**
     * @return array<mixed>
     */
    public function getSchoolHolidays(string $federal): array
    {
        $filteredHolidays = $this->storage->readSchoolHolidays($federal);
        $holidays = [];
        foreach ($filteredHolidays as $data) {
            $holidays[] = Event::fromArray($data, Types::EVENT_TYPE_SCHOOL_HOLIDAY);
        }
        return $holidays;
    }

    /**
     * @param array<mixed> $data
     */
    public function savePublicHolidays(array $data): void
    {
        $this->storage->writePublicHolidays($data);
    }

    /**
     * @param array<mixed> $data
     */
    public function saveSchoolHolidays(array $data): void
    {
        $this->storage->writeSchoolHolidays($data);
    }
}
