<?php

namespace Domain\Block;

use Domain\Time\TimeInterface;

/**
 * Interface BlockInterface
 */
interface BlockInterface
{
    /**
     * @param int    $numberBlock
     * @param string $text
     *
     * @return ProbableBlockInterface
     */
    public static function parseBlock($numberBlock, $text);

    /**
     * @return string
     */
    public function getFormattedBlock();

    /**
     * @return int
     */
    public function getId();

    /**
     * @return TimeInterface
     */
    public function getTimeBegin();

    /**
     * @return TimeInterface
     */
    public function getTimeEnd();

    /**
     * @return array
     */
    public function getLines();
}
