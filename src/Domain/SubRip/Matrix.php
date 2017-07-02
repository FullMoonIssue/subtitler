<?php

namespace Domain\SubRip;

use Domain\Block\BlockFilterIterator;
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
        $numberBlock = 0;
        $blocks = array_map(
            function($formattedBlock) use (&$numberBlock) {
                return Block::parseBlock(++$numberBlock, trim($formattedBlock));
            },
            iterator_to_array(new BlockFilterIterator(
                (new \ArrayIterator(preg_split("{\r\n\r\n|\n\n}", $contents)))
            ))
        );

        return new self($blocks);
    }
}
