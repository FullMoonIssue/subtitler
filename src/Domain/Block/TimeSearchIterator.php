<?php

namespace Domain\Block;

use Domain\Time\TimeInterface;

/**
 * Class TimeSearchIterator
 */
class TimeSearchIterator extends \FilterIterator
{
    /**
     * @var TimeInterface
     */
    private $time;

    /**
     * @var int
     */
    private $currentIndex;

    /**
     * @var bool
     */
    private $firstCheck;

    /**
     * @var bool
     */
    private $exactlyTimeFound;

    /**
     * @var bool
     */
    private $beforeBetweenTimeFound;

    /**
     * @var bool
     */
    private $afterBetweenTimeFound;

    /**
     * TimeSearchIterator constructor.
     *
     * @param \Iterator     $iterator
     * @param TimeInterface $time
     */
    public function __construct(\Iterator $iterator, TimeInterface $time)
    {
        parent::__construct($iterator);

        $this->time = $time;
        $this->currentIndex = -1;
        $this->firstCheck = true;
        $this->exactlyTimeFound = false;
        $this->beforeBetweenTimeFound = false;
        $this->afterBetweenTimeFound = false;
    }

    public function accept()
    {
        ++$this->currentIndex;
        if (!$this->exactlyTimeFound && !$this->afterBetweenTimeFound) {
            /** @var ProbableBlockInterface $probableBlock */
            $probableBlock = $this->getInnerIterator()->current();

            // The searched time is directly between the begin and the end of a block
            if ($probableBlock->searchByTime($this->time)) {
                $this->exactlyTimeFound = true;

                return true;
            }

            // The searched time is before the begin time of the first block
            if ($this->firstCheck && $probableBlock->getTimeBegin()->isGreaterThan($this->time)) {
                $this->exactlyTimeFound = true;
                $this->afterBetweenTimeFound = true;

                return false;
            }

            // The first in-between times is found, we now have the second
            if ($this->beforeBetweenTimeFound) {
                $this->afterBetweenTimeFound = true;

                return true;
            }

            // Let's check if we can found an in-between time
            if ($this->time->isGreaterThan($probableBlock->getTimeEnd())) {
                $this->getInnerIterator()->next();
                /** @var ProbableBlockInterface $probableBlock */
                $probableBlock = $this->getInnerIterator()->current();

                if (null !== $probableBlock) { // The next() would lead after the last element
                    if ($this->time->isLesserThan($probableBlock->getTimeBegin())) {
                        $this->beforeBetweenTimeFound = true;
                    }

                    $this->getInnerIterator()->rewind();
                    for ($index = 0; $index < $this->currentIndex; ++$index) {
                        $this->getInnerIterator()->next();
                    }

                    if ($this->beforeBetweenTimeFound) {
                        return true;
                    }
                }
            }

            $this->firstCheck = false;
        }

        return false;
    }
}
