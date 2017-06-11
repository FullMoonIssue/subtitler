<?php

namespace Action;

use Domain\MatrixInterface;

/**
 * Class Transform
 * @package Action
 */
class Transform implements TransformInterface
{
    /**
     * {@inheritdoc}
     */
    public function translate(MatrixInterface $matrix, $translation, $from, $to, $outputFile)
    {
        $matrix->translate($translation, $from, $to);
        file_put_contents($outputFile, $matrix->getFormattedMatrix());
    }
}
