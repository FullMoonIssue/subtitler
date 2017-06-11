<?php

namespace Action;

use Domain\MatrixInterface;

/**
 * Interface TransformInterface
 * @package Action
 */
interface TransformInterface
{
    /**
     * @param MatrixInterface $matrix
     * @param string $translation
     * @param int $from
     * @param int $to
     * @param string $outputFile
     */
    public function translate(MatrixInterface $matrix, $translation, $from, $to, $outputFile);
}
