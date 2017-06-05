<?php

namespace Action;

use Domain\Matrix;

/**
 * Class Search
 * @package Action
 */
class Search implements SearchInterface
{
    /**
     * {@inheritdoc}
     */
    public function search($inputFile, $searchByText, $searchByTime)
    {
        $founds = [];
        $matrix = Matrix::parseMatrix(file_get_contents($inputFile));
        foreach($matrix->getBlocks() as $block) {
            if (null !== $searchByText) {
                if ($block->searchByText($searchByText)) {
                    $founds[$block->getId()] = $block->getFormattedBlock();
                }
            } else {
                if ($block->searchByTime($searchByTime)) {
                    $founds[$block->getId()] = $block->getFormattedBlock();
                }
            }
        }

        return $founds;
    }
}