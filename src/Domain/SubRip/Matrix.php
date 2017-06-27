<?php

namespace Domain\SubRip;

use Domain\Matrix\Matrix as BaseMatrix;

/**
 * Class Matrix
 * @package Domain\SubRip
 */
class Matrix extends BaseMatrix
{
    /**
     * {@inheritdoc}
     */
    public static function parseMatrix($contents)
    {
        $blocks = [];
        $numberBlock = 0;
        foreach (preg_split("{\r\n\r\n|\n\n}", $contents) as $block) {
            if (!empty($block)) {
                $blocks[] = Block::parseBlock(++$numberBlock, trim($block));
            }
        }

        return new self($blocks);
    }
}
