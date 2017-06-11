<?php
namespace Domain\SubRip;

use Domain\Block as BaseBlock;
use Domain\Exception\BlockException;

/**
 * Class Block
 * @package Domain\SubRip
 */
class Block extends BaseBlock
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public static function parseBlock($numberBlock, $text)
    {
        $beginTime = null;
        $endTime = null;
        $lines = [];
        foreach (preg_split("{\r\n|\n}", $text) as $cpt => $line) {
            switch ($cpt) {
                case 0:
                    break;
                case 1:
                    list($beginTime, /* separator */, $endTime) = explode(' ', $line);
                    break;
                default:
                    if (!empty($line)) {
                        $lines[] = $line;
                    }
                    break;
            }
        }

        if (null === $beginTime) {
            throw new BlockException('The begin time is not found');
        }
        if (null === $endTime) {
            throw new BlockException('The end time is not found');
        }
        if (0 === count($lines)) {
            throw new BlockException('No lines found');
        }

        return new self(
            new Time($beginTime),
            new Time($endTime),
            $lines,
            $numberBlock
        );
    }
}
