<?php
namespace Domain;

use Domain\Exception\MatrixException;

/**
 * Class Matrix
 * @package Domain
 */
class Matrix
{
    /**
     * @var Block[]
     */
    private $blocks;

    /**
     * Matrix constructor.
     * @param Block[] $blocks
     */
    public function __construct(array $blocks)
    {
        $this->blocks = $blocks;
    }

    /**
     * @return Block[]
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @return string
     */
    public function getFormattedMatrix()
    {
        return implode("\n", array_map(function(Block $block) { return $block->getFormattedBlock(); }, $this->blocks));
    }

    /**
     * @param string $contents
     * @return Matrix
     */
    public static function parseMatrix($contents)
    {
        $blocks = [];
        foreach(preg_split("{\r\n\r\n|\n\n}", $contents) as $block) {
            if(!empty($block)) {
                $blocks[] = Block::parseBlock(trim($block));
            }
        }

        return new self($blocks);
    }

    /**
     * @param string $userTime
     * @param int $fromId
     * @param int|null $toId
     */
    public function translate($userTime, $fromId = 1, $toId = null)
    {
        if(!preg_match('=(?P<operation>[\+|-])(?P<count>\d+)(?P<unit>[h|m|s|u])=', $userTime, $matches)) {
            throw new MatrixException('Your translation value is not correct');
        }
        if($fromId < 1) {
            throw new MatrixException('You can translate only from a positive id');
        }
        if(null !== $toId && $toId < 1) {
            throw new MatrixException('You can translate only to a positive id');
        }
        if(null !== $toId && $toId < $fromId) {
            throw new MatrixException('The ending id have to be greater than the beginning in translation time');
        }

        $addOperation = ('+' == $matches['operation']);
        $count = (int) $matches['count'];
        $unit = $matches['unit'];
        $method = null;
        switch($unit) {
            case 'h':
                $method = ($addOperation ? 'addHours' : 'subtractHours');
                break;
            case 'm':
                $method = ($addOperation ? 'addMinutes' : 'subtractMinutes');
                break;
            case 's':
                $method = ($addOperation ? 'addSeconds' : 'subtractSeconds');
                break;
            case 'u':
                $method = ($addOperation ? 'addMilliSeconds' : 'subtractMilliSeconds');
                break;
        }

        $startTranslate = false;
        foreach($this->blocks as $block) {
            if($block->searchById($fromId)) {
                $startTranslate = true;
            }
            if($startTranslate) {
                $block->getTimeBegin()->$method($count);
                $block->getTimeEnd()->$method($count);
            }
            if(null !== $toId && $block->searchById($toId)) {
                break;
            }
        }
    }
}