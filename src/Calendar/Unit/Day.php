<?php

namespace App\Calendar\Unit;

class Day
{
    private \DateTime $date;

    public function setDate(\DateTime $date):void
    {
        $this->date = $date;
    }

    public function getDay(): int
    {
        return $this->date->format('j');
    }

    public function getWeekdayName(): string
    {
        return strftime('%a', $this->date->getTimestamp());
    }

    public function getFormatedDate($showWeekdayName=true)
    {
        return strftime(
            $showWeekdayName ? '%d %a' : '%d',
            $this->date->getTimestamp()
        );
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function getDayOfWeek()
    {
        return $this->date->format('N');
    }
}