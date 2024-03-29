<?php

namespace App\Tests\Calendar;

use Aeon\Calendar\Gregorian\DateTime;
use App\Calendar\Event;
use App\Calendar\EventException;
use App\Calendar\Events;
use PHPUnit\Framework\TestCase;

class EventsTest extends TestCase
{
    /** @var Events */
    private $sut;

    public function getEventsTestData()
    {
        $events = array();
        $start = new \DateTime('2017-01-01 10:00:00');
        for ($i=0; $i<3; $i++) {
            $end = clone $start;
            $end->add(new \DateInterval('P2D'));
            $event = new Event(Event\Types::EVENT_TYPE_CUSTOM);
            $event->setEventPeriod($start, $end);
            $events[]=$event;
            $start = $end;
        }

        return [
           'allWithin'  => [
               $events,
               DateTime::fromString('2017-01-01 10:00:00'),
               DateTime::fromString('2017-01-10 10:00:00'),
               3
           ]
        ];
    }

    /** @dataProvider getEventsTestData */
    public function testEvents($events, $calendarStart, $calenderEnd, $count)
    {
        $this->sut = new Events($events);
        $this->assertEquals(count($events), count($this->sut));

        $this->sut->addEvents($events);
        $this->assertEquals(count($events)*2, count($this->sut));

        $this->sut->setEvents($events);
        $this->assertEquals(count($events), count($this->sut));

        $filteredEvents = $this->sut->getEventsByRange($calendarStart, $calenderEnd);
        $this->assertEquals($count, count($filteredEvents));

        $iterationCount=0;
        foreach ($this->sut as $event) {
            $iterationCount++;
        }
        $this->assertEquals(count($events), $iterationCount);
    }

    public function testWrongEventSet()
    {
        $this->expectException(EventException::class);
        $this->sut = new Events();
        $this->sut->setEvents([new EventException()]);
    }
}
