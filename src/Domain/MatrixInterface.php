<?php

namespace Domain;

/**
 * Interface MatrixInterface
 * @package Domain
 */
interface MatrixInterface
{
    /**
     * @param string $contents
     * @return MatrixInterface
     */
    public static function parseMatrix($contents);

    /**
     * @return BlockInterface[]
     */
    public function getBlocks();

    /**
     * @return string
     */
    public function getFormattedMatrix();

    /**
     * @param string $userTime
     * @param int $fromId
     * @param int|null $toId
     */
    public function translate($userTime, $fromId = 1, $toId = null);
}
