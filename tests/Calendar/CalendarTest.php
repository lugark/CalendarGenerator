<?php

namespace App\Tests\Calendar;

use App\Calendar\Calendar;
use App\Calendar\Event;
use App\Calendar\Event\Types;
use App\Calendar\Unit\Month;
use PHPUnit\Framework\TestCase;

class CalendarTest extends TestCase
{
    public function testInitState()
    {
        $calendar = new Calendar();
        $this->assertEmpty($calendar->getActiveCalendarEvents());
        $this->assertEmpty($calendar->getData());
    }

    public function testCalendarGeneration()
    {
        $calendar = new Calendar(new \DateTime('2019-01'));
        $this->assertEmpty($calendar->getData());

        $calendar->generateCalendarData();
        $data = $calendar->getData();
        $this->assertEquals(12, count($data));
        $this->assertEquals(Month::class, get_class($data[1]));
    }

    public function testSetEventsFail()
    {
        $calendar = new Calendar();
        $calendar->setEvents(['event1', 'event2']);

        $this->expectExceptionMessage('Call to a member function');
        $calendar->getActiveCalendarEvents();
    }

    public function getEventTestData()
    {
        $futureEvent = new Event(Types::EVENT_TYPE_CUSTOM);
        $futureEvent->setStart(new \DateTime(strftime('+12 day')));
        $futureEvent->setEnd(new \DateTime(strftime('+13 day')));
        $futureEvent->setText('FutureEvent');

        $futureEvent2 = new Event(Types::EVENT_TYPE_CUSTOM);
        $futureEvent2->setStart(new \DateTime(strftime('+13 month')));
        $futureEvent2->setEnd(new \DateTime(strftime('+14 month')));
        $futureEvent2->setText('FutureEvent');

        $pastEvent = new Event(Types::EVENT_TYPE_CUSTOM);
        $pastEvent->setStart(new \DateTime(strftime('-2 day')));
        $pastEvent->setEnd(new \DateTime(strftime('-1 day')));
        $pastEvent->setText('FutureEvent');

        return [
            [
                [],
                []
            ],
            [
                [new Event(Types::EVENT_TYPE_CUSTOM), new Event(Types::EVENT_TYPE_PUBLIC_HOLIDAY)],
                []
            ],
            [
                [$futureEvent],
                [$futureEvent]
            ],
            [
                [$futureEvent, $pastEvent],
                [$futureEvent]
            ],
            [
                [$futureEvent, $pastEvent, $futureEvent2],
                [$futureEvent]
            ],
        ];
    }

    /**
     * @dataProvider getEventTestData
     */
    public function testSetEventsSuccess(array $events, array $expected)
    {
        $calendar = new Calendar();
        $calendar->generateCalendarData();

        $calendar->setEvents($events);
        $events = $calendar->getActiveCalendarEvents();

        $this->assertEquals($expected, $events);
    }

    /**
     * @dataProvider getEventTestData
     */
    public function testAddEvent(array $events, array $expected)
    {
        $calendar = new Calendar();
        $calendar->generateCalendarData();

        $calendar->addEvents($events);
        $calendar->addEvent(new Event(Types::EVENT_TYPE_CUSTOM));

        $events = $calendar->getActiveCalendarEvents();
        $this->assertEquals($expected, $events);
    }
}
