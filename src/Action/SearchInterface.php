<?php

namespace Action;

/**
 * Interface SearchInterface
 * @package Action
 */
interface SearchInterface
{
    /**
     * @param string $inputFile
     * @param string|null $searchByText
     * @param string|null $searchByTime
     *
     * @return array
     */
    public function search($inputFile, $searchByText, $searchByTime);
}