<?php

namespace Domain\Block;

use Domain\Time\TimeInterface;

/**
 * Class Block
 * @package Domain
 */
abstract class Block implements BlockInterface, ProbableInterface
{
    /**
     * @var TimeInterface
     */
    protected $timeBegin;

    /**
     * @var TimeInterface
     */
    protected $timeEnd;

    /**
     * @var array
     */
    protected $lines;

    /**
     * @var int
     */
    protected $id;

    /**
     * Block constructor.
     * @param TimeInterface $timeBegin
     * @param TimeInterface $timeEnd
     * @param array $lines
     * @param $id
     */
    public function __construct(TimeInterface $timeBegin, TimeInterface $timeEnd, array $lines, $id)
    {
        $this->timeBegin = $timeBegin;
        $this->timeEnd = $timeEnd;
        $this->lines = $lines;
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getTimeBegin()
    {
        return $this->timeBegin;
    }

    /**
     * {@inheritdoc}
     */
    public function getTimeEnd()
    {
        return $this->timeEnd;
    }

    /**
     * {@inheritdoc}
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * {@inheritdoc}
     */
    public function searchById($id)
    {
        return ($this->id === $id);
    }

    /**
     * {@inheritdoc}
     */
    public function searchByText($text)
    {
        return 0 < count(
            array_filter(
                $this->lines,
                function ($line) use ($text) {
                    return preg_match(sprintf('=%s=i', $text), $line);
                }
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function searchByTime(TimeInterface $time)
    {
        return $time->isGreaterOrEqualsThan($this->timeBegin) && $this->timeEnd->isGreaterOrEqualsThan($time);
    }
}
