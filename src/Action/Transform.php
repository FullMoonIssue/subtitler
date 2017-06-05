<?php

namespace Action;

use Domain\Matrix;

/**
 * Class Transform
 * @package Action
 */
class Transform implements TransformInterface
{
    /**
     * {@inheritdoc}
     */
    public function translate($inputFile, $translation, $from, $to, $outputFile)
    {
        $matrix = Matrix::parseMatrix(file_get_contents($inputFile));
        $matrix->translate($translation, $from, $to);
        file_put_contents($outputFile, $matrix->getFormattedMatrix());
    }
}