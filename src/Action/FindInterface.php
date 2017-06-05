<?php

namespace Action;

/**
 * Interface FindInterface
 * @package Action
 */
interface FindInterface
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