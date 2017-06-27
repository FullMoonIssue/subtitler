<?php

namespace Domain\Descriptor;

use Domain\Matrix\MatrixInterface;
use Domain\Time\TimeInterface;

/**
 * Interface DescriptorInterface
 * @package Domain\Descriptor
 */
interface DescriptorInterface
{
    /**
     * @return array
     */
    public function getExtensions();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $formattedTime
     * @return TimeInterface
     */
    public function buildTime($formattedTime);

    /**
     * @param string $contents
     * @return MatrixInterface
     */
    public function buildMatrix($contents);
}
