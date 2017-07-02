<?php

namespace Domain\Time;

/**
 * Interface TimeInterface
 */
interface TimeInterface
{
    /**
     * @return string
     */
    public function getFormattedTime();

    /**
     * @return \DateTime
     */
    public function getTime();

    /**
     * @param $formattedTime
     */
    public function buildDateTime($formattedTime);

    /**
     * @param int $nbHours
     *
     * @return $this
     */
    public function addHours($nbHours);

    /**
     * @param int $nbHours
     *
     * @return $this
     */
    public function subtractHours($nbHours);

    /**
     * @param int $nbMinutes
     *
     * @return $this
     */
    public function addMinutes($nbMinutes);

    /**
     * @param int $nbMinutes
     *
     * @return $this
     */
    public function subtractMinutes($nbMinutes);

    /**
     * @param int $nbSeconds
     *
     * @return $this
     */
    public function addSeconds($nbSeconds);

    /**
     * @param int $nbSeconds
     *
     * @return $this
     */
    public function subtractSeconds($nbSeconds);

    /**
     * @param int $nbMilliSeconds
     *
     * @return $this
     */
    public function addMilliSeconds($nbMilliSeconds);

    /**
     * @param int $nbMilliSeconds
     *
     * @return $this
     */
    public function subtractMilliSeconds($nbMilliSeconds);

    /**
     * @param TimeInterface $time
     *
     * @return bool
     */
    public function isGreaterThan(TimeInterface $time);

    /**
     * @param TimeInterface $time
     *
     * @return bool
     */
    public function isGreaterOrEqualsThan(TimeInterface $time);

    /**
     * @param TimeInterface $time
     *
     * @return bool
     */
    public function isLesserThan(TimeInterface $time);
}
