<?php

namespace Domain\Matrix;

use Domain\Block\ProbableBlockInterface;

/**
 * Interface MatrixInterface
 */
interface MatrixInterface
{
    /**
     * @param string $contents
     *
     * @return MatrixInterface
     */
    public static function parseMatrix($contents);

    /**
     * @return ProbableBlockInterface[]
     */
    public function getBlocks();

    /**
     * @return string
     */
    public function getFormattedMatrix();

    /**
     * @param string   $userTime
     * @param int      $fromId
     * @param int|null $toId
     */
    public function translate($userTime, $fromId = 1, $toId = null);
}
