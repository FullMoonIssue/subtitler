<?php
namespace Domain;

use Domain\Exception\BlockException;

/**
 * Class Block
 * @package Domain
 */
class Block
{
    /**
     * @var Time
     */
    private $timeBegin;

    /**
     * @var Time
     */
    private $timeEnd;

    /**
     * @var array
     */
    private $lines;

    /**
     * @var int
     */
    private $id;

    /**
     * Block constructor.
     * @param Time $timeBegin
     * @param Time $timeEnd
     * @param array $lines
     * @param int $id
     */
    public function __construct(Time $timeBegin, Time $timeEnd, array $lines, $id)
    {
        $this->timeBegin = $timeBegin;
        $this->timeEnd = $timeEnd;
        $this->lines = $lines;
        $this->id = $id;
    }

    /**
     * @return Time
     */
    public function getTimeBegin()
    {
        return $this->timeBegin;
    }

    /**
     * @return Time
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFormattedBlock()
    {
        return
            $this->id."\n"
            .$this->timeBegin->getFormattedTime()
            .' --> '
            .$this->timeEnd->getFormattedTime()."\n"
            .implode("\n", $this->lines)."\n";
    }

    /**
     * @param string $text
     * @return Block
     */
    public static function parseBlock($text)
    {
        $id = null;
        $beginTime = null;
        $endTime = null;
        $lines = [];
        foreach(preg_split("{\r\n|\n}", $text) as $cpt => $line) {
            switch ($cpt) {
                case 0:
                    $id = (int) $line;
                    if(0 === $id) { // Weird case where an unknown character is "untrimable"
                        $id = 1;
                    }
                    break;
                case 1:
                    list($beginTime, , $endTime) = explode(' ', $line);
                    break;
                default:
                    if(!empty($line)) {
                        $lines[] = $line;
                    }
                    break;
            }
        }

        if(null === $id) {
            throw new BlockException('The id is not found');
        }
        if(null === $beginTime) {
            throw new BlockException('The begin time is not found');
        }
        if(null === $endTime) {
            throw new BlockException('The end time is not found');
        }
        if(0 === count($lines)) {
            throw new BlockException('No lines found');
        }

        return new self(
            new Time($beginTime),
            new Time($endTime),
            $lines,
            $id
        );
    }

    /**
     * @param int $id
     * @return bool
     */
    public function searchById($id)
    {
        return ($this->id === $id);
    }

    /**
     * @param string $text
     * @return bool
     */
    public function searchByText($text)
    {
        return 0 < count(
            array_filter(
                $this->lines,
                function($line) use ($text) {
                    return preg_match(sprintf('=%s=i', $text), $line);
                }
            )
        );
    }

    /**
     * @param string $formattedTime
     * @return bool
     */
    public function searchByTime($formattedTime)
    {
        $searchTime = (new Time($formattedTime))->getTime();

        $timeBegin = $this->timeBegin->getTime();
        $timeEnd = $this->timeEnd->getTime();
        // The calculation is done by converting DateTime into micro seconds
        $msBegin = $timeBegin->getTimestamp() * 1000000 + (int) $timeBegin->format('u');
        $msEnd = $timeEnd->getTimestamp() * 1000000 + (int) $timeEnd->format('u');
        $msSearch = $searchTime->getTimestamp() * 1000000 + (int) $searchTime->format('u');

        return $msBegin <= $msSearch && $msSearch <= $msEnd;
    }
}