<?php

use Pimple\Container;
use Domain\Descriptor\DescriptorRegistry;

$container = new Container();

$container['descriptor_registry'] = function () {
    $descriptorRegistry = new DescriptorRegistry();
    $descriptorRegistry->addDescriptor(new \Domain\SubRip\Descriptor());

    return $descriptorRegistry;
};

return $container;