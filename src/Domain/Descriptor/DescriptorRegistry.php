<?php

namespace Domain\Descriptor;

use Domain\Exception\DescriptorException;

/**
 * Class DescriptorRegistry
 * @package Domain\Descriptor
 */
class DescriptorRegistry
{
    /**
     * @var DescriptorInterface[]
     */
    private $descriptors;

    /**
     * DescriptorRegistry constructor.
     */
    public function __construct()
    {
        $this->descriptors = [];
    }

    /**
     * @param DescriptorInterface $descriptor
     * @return $this
     */
    public function addDescriptor(DescriptorInterface $descriptor)
    {
        $this->descriptors[$descriptor->getName()] = $descriptor;

        return $this;
    }

    /**
     * @param $name
     * @return DescriptorInterface
     */
    public function getDescriptor($name)
    {
        if (!isset($this->descriptors[$name])) {
            throw new DescriptorException(sprintf('The descriptor "%s" does not exist', $name));
        }

        return $this->descriptors[$name];
    }

    /**
     * @return array
     */
    public function getSupportedExtensions()
    {
        $extensions = [];
        foreach ($this->descriptors as $descriptor) {
            $extensions = array_merge($extensions, $descriptor->extensionsSupported());
        }

        return $extensions;
    }

    /**
     * @param string $extension
     * @return DescriptorInterface|null
     */
    public function searchDescriptor($extension)
    {
        foreach ($this->descriptors as $descriptor) {
            if (in_array($extension, $descriptor->getExtensions())) {
                return $descriptor;
            }
        }

        return null;
    }
}
