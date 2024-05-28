<?php

namespace App\Tests\Serializer\Normalizer;

use App\Serializer\Normalizer\EventNormalizer;
use Calendar\Pdf\Renderer\Event\Event;
use Calendar\Pdf\Renderer\Event\Types;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class EventNormalizerTest extends TestCase
{

    /** @var EventNormalizer */
    protected $sut;

    public function setUp(): void
    {
        $this->sut = new EventNormalizer();
    }

    public function testInterface(): void
    {
        $this->assertInstanceOf(DenormalizerInterface::class, $this->sut);
    }

    public function testSupportsDenormalization(): void
    {
        $this->assertTrue($this->sut->supportsDenormalization([], Event::class));
    }

    public function testSupportsDenormalizationFail(): void
    {
        $this->assertFalse($this->sut->supportsDenormalization([], TestCase::class));
    }

    public function denormalizeProvider()
    {
        $eventOnlyName = new Event(Types::EVENT_TYPE_SCHOOL_HOLIDAY);
        $eventOnlyName->setText('TestEvent');

        $eventComplete = new Event(Types::EVENT_TYPE_SCHOOL_HOLIDAY);
        $eventComplete->setText('Complete')
            ->setEventPeriod(new \DateTime('01-01-2020'), new \DateTime('02-01-2020'));

        $eventCompleteOnlyDate = new Event(Types::EVENT_TYPE_SCHOOL_HOLIDAY);
        $eventCompleteOnlyDate->setText('CompleteOnlyDate')
            ->setEventPeriod(new \DateTime('01-01-2020'));

        return [
            [
                ['someRandomArray' => 'uselessData'],
                null
            ],
            [
                ['name' => 'TestEvent'],
                $eventOnlyName
            ],
            [
                ['name' => 'Complete', 'start' => '01-01-2020', 'end' => '02-01-2020'],
                $eventComplete
            ],
            [
                ['name' => 'CompleteOnlyDate', 'date' => '01-01-2020'],
                $eventCompleteOnlyDate
            ]
        ];
    }

    /** @dataProvider  denormalizeProvider */
    public function testDenormalize($input, $output): void
    {
        $result = $this->sut->denormalize(
            $input,
            Event::class,
            null,
            ['eventType' => Types::EVENT_TYPE_SCHOOL_HOLIDAY]
        );

        $this->assertEquals($output, $result);
    }
}
