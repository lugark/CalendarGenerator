<?php

namespace App\Tests\Serializer\Normalizer;

use App\Calendar\Event;
use App\Serializer\Normalizer\EventNormalizer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class EventNormalizerTest extends TestCase
{

    /** @var MockObject */
    protected $sut;

    public function setUp()
    {
        parent::setUp();
        $this->sut = new EventNormalizer();
        $this->sut->setSerializer(new Serializer([new DateTimeNormalizer()]));
    }

    public function testInterface()
    {
        $this->assertInstanceOf(NormalizerInterface::class, $this->sut);
        $this->assertInstanceOf(DenormalizerInterface::class, $this->sut);
    }

    public function testSupportsDenormalization()
    {
        $this->assertTrue($this->sut->supportsDenormalization([], Event::class));
    }

    public function testSupportsDenormalizationFail()
    {
        $this->assertFalse($this->sut->supportsDenormalization([], TestCase::class));
    }

    public function denormalizeProvider()
    {
        $eventOnlyName = new Event(Event\Types::EVENT_TYPE_SCHOOL_HOLIDAY);
        $eventOnlyName->setText('TestEvent');

        $eventComplete = new Event(Event\Types::EVENT_TYPE_SCHOOL_HOLIDAY);
        $eventComplete->setText('Complete')
            ->setStart(new \DateTime('01-01-2020'))
            ->setEnd(new \DateTime('02-01-2020'));

        $eventCompleteOnlyDate = new Event(Event\Types::EVENT_TYPE_SCHOOL_HOLIDAY);
        $eventCompleteOnlyDate->setText('CompleteOnlyDate')
            ->setStart(new \DateTime('01-01-2020'));

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
    public function testDenormalize($input, $output)
    {
        $result = $this->sut->denormalize(
            $input,
            Event::class,
            null,
            ['eventType' => Event\Types::EVENT_TYPE_SCHOOL_HOLIDAY]
        );

        $this->assertEquals($output, $result);
    }

    public function testSupportsNormalization()
    {
        $this->markTestSkipped('skipped because wrong usage');
        $this->assertTrue($this->sut->supportsNormalization([], Event::class));
    }

    public function testSupportsNormalizationFail()
    {
        $this->assertFalse($this->sut->supportsNormalization([], TestCase::class));
    }

    public function testNormalize()
    {
        $this->assertEmpty($this->sut->normalize(['something' => 'useless']));
    }
}
