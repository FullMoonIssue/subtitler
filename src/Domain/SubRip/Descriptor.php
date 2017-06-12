<?php

namespace Domain\SubRip;

use Domain\Descriptor\DescriptorInterface;

/**
 * Class Descriptor
 * @package Domain\SubRip
 */
class Descriptor implements DescriptorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        return ['srt'];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'subrip';
    }

    /**
     * {@inheritdoc}
     */
    public function buildTime($formattedTime)
    {
        return new Time($formattedTime);
    }

    /**
     * {@inheritdoc}
     */
    public function buildMatrix($contents)
    {
        return Matrix::parseMatrix($contents);
    }
}
