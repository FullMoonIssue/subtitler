<?php

namespace Tests\Domain\SubRip;

use Domain\SubRip\Time;

/**
 * Class TimeTest
 * @package Tests\Domain
 */
class TimeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group Time
     */
    public function testCreateTime()
    {
        $time = new Time('00:46:58,740');

        $this->assertEquals(
            (new \DateTime('1970-01-01 00:46:58.740')),
            $time->getTime()
        );
        $this->assertEquals(
            '00:46:58,740',
            $time->getFormattedTime()
        );
    }

    /**
     * @group Time
     */
    public function testAddTime()
    {
        $t = new Time('00:46:58,740');
        $this->assertEquals(
            '01:46:58,740',
            $t->addHours(1)->getFormattedTime()
        );

        $t = new Time('00:58:58,740');
        $this->assertEquals(
            '01:00:58,740',
            $t->addMinutes(2)->getFormattedTime()
        );

        $t = new Time('00:59:58,740');
        $this->assertEquals(
            '01:00:02,740',
            $t->addSeconds(4)->getFormattedTime()
        );

        $t = new Time('00:59:59,740');
        $this->assertEquals(
            '01:00:00,000',
            $t->addMilliSeconds(260)->getFormattedTime()
        );
    }

    /**
     * @group Time
     */
    public function testSubtractTime()
    {
        $t = new Time('01:46:58,740');
        $this->assertEquals(
            '00:46:58,740',
            $t->subtractHours(1)->getFormattedTime()
        );

        $t = new Time('01:01:58,740');
        $this->assertEquals(
            '00:59:58,740',
            $t->subtractMinutes(2)->getFormattedTime()
        );

        $t = new Time('01:00:02,740');
        $this->assertEquals(
            '00:59:58,740',
            $t->subtractSeconds(4)->getFormattedTime()
        );

        $t = new Time('01:00:00,250');
        $this->assertEquals(
            '00:59:59,990',
            $t->subtractMilliSeconds(260)->getFormattedTime()
        );
    }

    /**
     * @group Time
     * @expectedException \Domain\Exception\TimeException
     * @expectedExceptionMessage Only positive hours
     */
    public function testAddNegativeHours()
    {
        $t = new Time('23:46:58,740');
        $t->addHours(-1);
    }

    /**
     * @group Time
     * @expectedException \Domain\Exception\TimeException
     * @expectedExceptionMessage Only positive hours
     */
    public function testSubtractNegativeHours()
    {
        $t = new Time('23:46:58,740');
        $t->subtractHours(-1);
    }

    /**
     * @group Time
     * @expectedException \Domain\Exception\TimeException
     * @expectedExceptionMessage Only positive minutes
     */
    public function testAddNegativeMinutes()
    {
        $t = new Time('23:46:58,740');
        $t->addMinutes(-1);
    }

    /**
     * @group Time
     * @expectedException \Domain\Exception\TimeException
     * @expectedExceptionMessage Only positive minutes
     */
    public function testSubtractNegativeMinutes()
    {
        $t = new Time('23:46:58,740');
        $t->subtractMinutes(-1);
    }

    /**
     * @group Time
     * @expectedException \Domain\Exception\TimeException
     * @expectedExceptionMessage Only positive seconds
     */
    public function testAddNegativeSeconds()
    {
        $t = new Time('23:46:58,740');
        $t->addSeconds(-1);
    }

    /**
     * @group Time
     * @expectedException \Domain\Exception\TimeException
     * @expectedExceptionMessage Only positive seconds
     */
    public function testSubtractNegativeSeconds()
    {
        $t = new Time('23:46:58,740');
        $t->subtractSeconds(-1);
    }

    /**
     * @group Time
     * @expectedException \Domain\Exception\TimeException
     * @expectedExceptionMessage Only positive milli seconds
     */
    public function testAddNegativeMilliSeconds()
    {
        $t = new Time('23:46:58,740');
        $t->addMilliSeconds(-1);
    }

    /**
     * @group Time
     * @expectedException \Domain\Exception\TimeException
     * @expectedExceptionMessage Only positive milli seconds
     */
    public function testSubtractNegativeMilliSeconds()
    {
        $t = new Time('23:46:58,740');
        $t->subtractMilliSeconds(-1);
    }

    /**
     * @group Time
     * @expectedException \Domain\Exception\TimeException
     * @expectedExceptionMessage Nope, no duration more or equals than 24 hours is handled
     */
    public function testDurationMoreOrEqualsThan24Hours()
    {
        $t = new Time('23:46:58,740');
        $t->addHours(1)->getFormattedTime();
    }

    /**
     * @group Time
     * @expectedException \Domain\Exception\TimeException
     * @expectedExceptionMessage Nope, no duration less than 0 second is handled
     */
    public function testDurationLessThan0Hour()
    {
        $t = new Time('00:46:58,740');
        $t->subtractHours(1)->getFormattedTime();
    }

    /**
     * @group Time
     * @expectedException \Domain\Exception\TimeException
     * @expectedExceptionMessage Just add between 1 and 23 hours
     */
    public function testDurationHoursToAddTooHigh()
    {
        $t = new Time('00:46:58,740');
        $t->addHours(24)->getFormattedTime();
    }

    /**
     * @group Time
     * @expectedException \Domain\Exception\TimeException
     * @expectedExceptionMessage Just subtract between 1 and 23 hours
     */
    public function testDurationHoursToSubtractTooHigh()
    {
        $t = new Time('00:46:58,740');
        $t->subtractHours(24)->getFormattedTime();
    }

    /**
     * @group Time
     * @expectedException \Domain\Exception\TimeException
     * @expectedExceptionMessage Just add between 1 and 59 minutes or hours instead
     */
    public function testDurationMinutesToAddTooHigh()
    {
        $t = new Time('00:46:58,740');
        $t->addMinutes(60)->getFormattedTime();
    }

    /**
     * @group Time
     * @expectedException \Domain\Exception\TimeException
     * @expectedExceptionMessage Just subtract between 1 and 59 minutes or hours instead
     */
    public function testDurationMinutesToSubtractTooHigh()
    {
        $t = new Time('00:46:58,740');
        $t->subtractMinutes(60)->getFormattedTime();
    }

    /**
     * @group Time
     * @expectedException \Domain\Exception\TimeException
     * @expectedExceptionMessage Just add between 1 and 59 seconds or minutes instead
     */
    public function testDurationSecondsToAddTooHigh()
    {
        $t = new Time('00:46:58,740');
        $t->addSeconds(60)->getFormattedTime();
    }

    /**
     * @group Time
     * @expectedException \Domain\Exception\TimeException
     * @expectedExceptionMessage Just subtract between 1 and 59 seconds or minutes instead
     */
    public function testDurationSecondsToSubtractTooHigh()
    {
        $t = new Time('00:46:58,740');
        $t->subtractSeconds(60)->getFormattedTime();
    }

    /**
     * @group Time
     * @expectedException \Domain\Exception\TimeException
     * @expectedExceptionMessage Just add between 1 and 999 milliseconds or seconds instead
     */
    public function testDurationMilliSecondsToAddTooHigh()
    {
        $t = new Time('00:46:58,740');
        $t->addMilliSeconds(1000)->getFormattedTime();
    }

    /**
     * @group Time
     * @expectedException \Domain\Exception\TimeException
     * @expectedExceptionMessage Just subtract between 1 and 999 milliseconds or seconds instead
     */
    public function testDurationMilliSecondsToSubtractTooHigh()
    {
        $t = new Time('00:46:58,740');
        $t->subtractMilliSeconds(1000)->getFormattedTime();
    }
}
