<?php

namespace Domain\Descriptor;

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
     * @return string
     */
    public function getTimeConstructor();

    /**
     * @return string
     */
    public function getMatrixConstructor();
}
