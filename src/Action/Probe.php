<?php

namespace Action;

use Domain\Block\ProbableBlockInterface;
use Domain\Block\TextualSearchIterator;
use Domain\Block\TimeSearchIterator;
use Domain\Matrix\MatrixInterface;
use Domain\Time\TimeInterface;

/**
 * Class Probe
 * @package Action
 */
class Probe implements ProbeInterface
{
    /**
     * {@inheritdoc}
     */
    public function search(MatrixInterface $matrix, $searchByText = null, TimeInterface $searchByTime = null)
    {
        $founds = [];
        $iterator = (
            !$searchByText
            ? new TimeSearchIterator(new \ArrayIterator($matrix->getBlocks()), $searchByTime)
            : new TextualSearchIterator(new \ArrayIterator($matrix->getBlocks()), $searchByText)
        );

        $block = null;
        /** @var ProbableBlockInterface $block */
        foreach($iterator as $block) {
            $founds[$block->getId()] = $block->getFormattedBlock();
        }

        return $founds;
    }
}
