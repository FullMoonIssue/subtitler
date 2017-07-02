<?php

namespace Domain\SubRip;

use Domain\Time\Time as BaseTime;

/**
 * Class Time
 */
class Time extends BaseTime
{
    /**
     * {@inheritdoc}
     */
    public function getFormattedTime()
    {
        // Show milliseconds not microseconds
        return substr($this->time->format('H:i:s,u'), 0, -3);
    }

    /**
     * {@inheritdoc}
     */
    public function buildDateTime($formattedTime)
    {
        preg_match(
            '/(?P<hours>\d{2}):(?P<minutes>\d{2}):(?P<seconds>\d{2}),(?P<milliseconds>\d{3})/',
            $formattedTime,
            $matches
        );

        $this->constructDateTime([
            $matches['hours'],
            $matches['minutes'],
            $matches['seconds'],
            $matches['milliseconds'],
        ]);
    }
}
