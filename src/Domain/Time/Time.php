<?php

namespace Domain\Time;

use Domain\Exception\TimeException;

/**
 * Class Time
 */
abstract class Time implements TimeInterface
{
    /**
     * @var \DateTime
     */
    protected $time;

    /**
     * Time constructor.
     * @param string $formattedTime
     */
    public function __construct($formattedTime)
    {
        $this->buildDateTime($formattedTime);
    }

    /**
     * {@inheritdoc}
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * {@inheritdoc}
     */
    public function addHours($nbHours)
    {
        if (!$this->isPositiveValue($nbHours)) {
            throw new TimeException('Only positive hours');
        }
        if (24 <= $nbHours) {
            throw new TimeException('Just add between 1 and 23 hours');
        }

        $this->time->add(new \DateInterval(sprintf('PT%dH', $nbHours)));

        $this->checkTime();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function subtractHours($nbHours)
    {
        if (!$this->isPositiveValue($nbHours)) {
            throw new TimeException('Only positive hours');
        }
        if (24 <= $nbHours) {
            throw new TimeException('Just subtract between 1 and 23 hours');
        }

        $this->time->sub(new \DateInterval(sprintf('PT%dH', $nbHours)));

        $this->checkTime();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addMinutes($nbMinutes)
    {
        if (!$this->isPositiveValue($nbMinutes)) {
            throw new TimeException('Only positive minutes');
        }
        if (60 <= $nbMinutes) {
            throw new TimeException('Just add between 1 and 59 minutes or hours instead');
        }

        $this->time->add(new \DateInterval(sprintf('PT%dM', $nbMinutes)));

        $this->checkTime();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function subtractMinutes($nbMinutes)
    {
        if (!$this->isPositiveValue($nbMinutes)) {
            throw new TimeException('Only positive minutes');
        }
        if (60 <= $nbMinutes) {
            throw new TimeException('Just subtract between 1 and 59 minutes or hours instead');
        }

        $this->time->sub(new \DateInterval(sprintf('PT%dM', $nbMinutes)));

        $this->checkTime();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addSeconds($nbSeconds)
    {
        if (!$this->isPositiveValue($nbSeconds)) {
            throw new TimeException('Only positive seconds');
        }
        if (60 <= $nbSeconds) {
            throw new TimeException('Just add between 1 and 59 seconds or minutes instead');
        }

        $this->time->add(new \DateInterval(sprintf('PT%dS', $nbSeconds)));

        $this->checkTime();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function subtractSeconds($nbSeconds)
    {
        if (!$this->isPositiveValue($nbSeconds)) {
            throw new TimeException('Only positive seconds');
        }
        if (60 <= $nbSeconds) {
            throw new TimeException('Just subtract between 1 and 59 seconds or minutes instead');
        }

        $this->time->sub(new \DateInterval(sprintf('PT%dS', $nbSeconds)));

        $this->checkTime();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addMilliSeconds($nbMilliSeconds)
    {
        if (!$this->isPositiveValue($nbMilliSeconds)) {
            throw new TimeException('Only positive milli seconds');
        }
        if (1000 <= $nbMilliSeconds) {
            throw new TimeException('Just add between 1 and 999 milliseconds or seconds instead');
        }

        $currentNbMilliSeconds = (int) $this->time->format('u') + ($nbMilliSeconds * 1000);
        if (1000000 <= $currentNbMilliSeconds) {
            $currentNbMilliSeconds -= 1000000;
            $this->addSeconds(1);
        }

        $this->reconstructDateTimeWithMilliSeconds($currentNbMilliSeconds);
        $this->checkTime();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function subtractMilliSeconds($nbMilliSeconds)
    {
        if (!$this->isPositiveValue($nbMilliSeconds)) {
            throw new TimeException('Only positive milli seconds');
        }
        if (1000 <= $nbMilliSeconds) {
            throw new TimeException('Just subtract between 1 and 999 milliseconds or seconds instead');
        }

        $currentNbMilliSeconds = (int) $this->time->format('u') - ($nbMilliSeconds * 1000);
        if (0 > $currentNbMilliSeconds) {
            $currentNbMilliSeconds = 1000000 - abs($currentNbMilliSeconds);
            $this->subtractSeconds(1);
        }

        $this->reconstructDateTimeWithMilliSeconds($currentNbMilliSeconds);
        $this->checkTime();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isGreaterThan(TimeInterface $time)
    {
        return $this->getMicroSeconds($this) > $this->getMicroSeconds($time);
    }

    /**
     * {@inheritdoc}
     */
    public function isGreaterOrEqualsThan(TimeInterface $time)
    {
        return $this->getMicroSeconds($this) >= $this->getMicroSeconds($time);
    }

    /**
     * {@inheritdoc}
     */
    public function isLesserThan(TimeInterface $time)
    {
        return $this->getMicroSeconds($this) < $this->getMicroSeconds($time);
    }

    /**
     * @param TimeInterface $time
     * @return int
     */
    protected function getMicroSeconds(TimeInterface $time)
    {
        return $time->getTime()->getTimestamp() * 1000000 + (int) $time->getTime()->format('u');
    }

    /**
     * @param int $value
     * @return bool
     */
    protected function isPositiveValue($value)
    {
        return (0 < $value);
    }

    /**
     * @throws TimeException
     */
    protected function checkTime()
    {
        if (1969 >= (int) $this->time->format('Y')) {
            throw new TimeException('Nope, no duration less than 0 second is handled');
        }

        if (2 <= (int) $this->time->format('d')) {
            throw new TimeException('Nope, no duration more or equals than 24 hours is handled');
        }
    }

    /**
     * @param int $nbMilliSeconds
     */
    protected function reconstructDateTimeWithMilliSeconds($nbMilliSeconds)
    {
        $nbMilliSeconds = str_pad($nbMilliSeconds, 6, '0', STR_PAD_LEFT);
        $timeParts = explode(':', $this->time->format('H:i:s'));
        $timeParts[] = $nbMilliSeconds;
        $this->constructDateTime($timeParts);
    }

    /**
     * @param array $timeParts
     */
    protected function constructDateTime(array $timeParts)
    {
        $this->time = new \DateTime(
            vsprintf(
                '1970-01-01 %s:%s:%s.%s',
                $timeParts
            ),
            new \DateTimeZone('UTC')
        );
    }
}
