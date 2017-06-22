<?php

namespace Action;

use Domain\MatrixInterface;
use Domain\TimeInterface;

/**
 * Interface ProbeInterface
 * @package Action
 */
interface ProbeInterface
{
    /**
     * @param MatrixInterface $matrix
     * @param string|null $searchByText
     * @param TimeInterface|null $searchByTime
     * @return array
     */
    public function search(MatrixInterface $matrix, $searchByText = null, TimeInterface $searchByTime = null);
}