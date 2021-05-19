<?php

namespace App\Calendar;

use Aeon\Calendar\Gregorian\DateTime;
use App\Calendar\Event;
use App\Calendar\Unit\Day;
use App\Calendar\Unit\Month;

class Calendar
{
    /** @var \DateTime */
    private $startDate;

    /** @var array */
    private $calendarData = [];

    /** @var Event[] */
    private $events = [];

    public function __construct(\DateTime $startDate=null)
    {
        if (empty($startDate)) {
            $startDate = new \DateTime();
        }

        $this->setStartDate($startDate);
    }

    public function generateCalendarData(int $numberOfMonths = 12): Calendar
    {
        $date = clone $this->startDate;
        $this->resetCalendar();

        for ($i=0; $i<$numberOfMonths; $i++) {
            $month = new Month(
                strftime('%B', $date->getTimestamp()),
                $date->format('Y')
            );
            for ($day=1; $day<=$date->format('t'); $day++) {
                $month->addDay(
                    $this->prepareDayObject($date->format('Y-m') . '-' . $day)
                );
            }
            $this->calendarData[] = $month;
            $date->modify('+1 month');
        }

        return $this;
    }

    private function prepareDayObject($shortDate)
    {
        $date = new \DateTime($shortDate);
        $day = new Day();
        $day->setDate($date);

        return $day;
    }

    public function setStartDate(\DateTime $startDate): void
    {
        $this->startDate = $startDate;
        $this->startDate->setTime(0,0);
    }

    private function resetCalendar(): void
    {
        $this->calendarData = [];
    }

    public function addEvent(Event $event): void
    {
        $this->events[] = $event;
    }

    public function addEvents(array $events): void
    {
        if (!empty($events)) {
            $this->events = array_merge($this->events, $events);
        }
    }

    public function setEvents(array $events)
    {
        $this->events = $events;
    }

    public function getData(): array
    {
        return $this->calendarData;
    }

    public function getActiveCalendarEvents(): array
    {
        $start = DateTime::fromDateTime($this->startDate);
        $end = $start->addMonths(count($this->calendarData));
        return array_filter($this->events, function ($event) use ($start, $end) {
            /** @var Event $event */
            return $event->isInRange($start, $end);
        });
    }
}