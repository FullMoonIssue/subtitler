<?php
namespace Domain;

/**
 * Class Block
 * @package Domain
 */
abstract class Block implements BlockInterface
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
     * @return TimeInterface
     */
    public function getTimeEnd()
    {
        return $this->timeEnd;
    }

    /**
     * @return array
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
        $searchTime = $time->getTime();

        $timeBegin = $this->timeBegin->getTime();
        $timeEnd = $this->timeEnd->getTime();
        // The calculation is done by converting DateTime into micro seconds
        $msBegin = $timeBegin->getTimestamp() * 1000000 + (int) $timeBegin->format('u');
        $msEnd = $timeEnd->getTimestamp() * 1000000 + (int) $timeEnd->format('u');
        $msSearch = $searchTime->getTimestamp() * 1000000 + (int) $searchTime->format('u');

        return $msBegin <= $msSearch && $msSearch <= $msEnd;
    }
}
