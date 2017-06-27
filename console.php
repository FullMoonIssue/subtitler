#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';
$container = include __DIR__.'/container.php';

use Symfony\Component\Console\Application;
use Command\TranslateTimeCommand;
use Command\SearchCommand;
use Action\Probe;

$application = new Application();
$application->add(new TranslateTimeCommand($container['descriptor_registry']));
$application->add(new SearchCommand(new Probe(), $container['descriptor_registry']));
$application->run();