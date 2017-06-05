<?php

namespace Action;

/**
 * Interface TransformInterface
 * @package Action
 */
interface TransformInterface
{
    /**
     * @param string $inputFile
     * @param string $translation
     * @param int $from
     * @param int $to
     * @param string $outputFile
     */
    public function translate($inputFile, $translation, $from, $to, $outputFile);
}