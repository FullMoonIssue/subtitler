<?php

namespace Action;

use Domain\Matrix;
use Domain\Time;

/**
 * Class Find
 * @package Action
 */
class Find implements FindInterface
{
    /**
     * @var array
     */
    private $lastRecord;

    /**
     * {@inheritdoc}
     */
    public function search($inputFile, $searchByText, $searchByTime)
    {
        $founds = [];
        $dtSearch = (!$searchByTime ?: new Time($searchByTime));
        $matrix = Matrix::parseMatrix(file_get_contents($inputFile));
        foreach($matrix->getBlocks() as $block) {
            if (null !== $searchByText) {
                if ($block->searchByText($searchByText)) {
                    $founds[$block->getId()] = $block->getFormattedBlock();
                }
            } else {
                if ($block->searchByTime($searchByTime)) {
                    $founds[$block->getId()] = $block->getFormattedBlock();
                    break;
                } else {
                    if($block->getTimeBegin()->getTime() > $dtSearch->getTime()) {
                        $founds[$this->lastRecord['id']] = $this->lastRecord['block'];
                        $founds[$block->getId()] = $block->getFormattedBlock();
                        break;
                    }
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