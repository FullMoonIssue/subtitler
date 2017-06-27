<?php

namespace Action;

use Domain\Matrix\MatrixInterface;
use Domain\Time\TimeInterface;

/**
 * Class Probe
 * @package Action
 */
class Probe implements ProbeInterface
{
    /**
     * @var array
     */
    private $lastRecord;

    /**
     * {@inheritdoc}
     */
    public function search(MatrixInterface $matrix, $searchByText = null, TimeInterface $searchByTime = null)
    {
        $founds = [];
        foreach ($matrix->getBlocks() as $block) {
            if (null !== $searchByText) {
                if ($block->searchByText($searchByText)) {
                    $founds[$block->getId()] = $block->getFormattedBlock();
                }
            } else {
                if ($block->getTimeBegin()->isGreaterThan($searchByTime)) {
                    if (!empty($this->lastRecord['id'])) {
                        $founds[$this->lastRecord['id']] = $this->lastRecord['block'];
                        $founds[$block->getId()] = $block->getFormattedBlock();
                    }
                    break;
                }
                if ($block->searchByTime($searchByTime)) {
                    $founds[$block->getId()] = $block->getFormattedBlock();
                    break;
                } else {
                    $this->lastRecord = [
                        'id'    => $block->getId(),
                        'block' => $block->getFormattedBlock()
                    ];
                }
            }
        }

        return $founds;
    }
}
