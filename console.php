#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';
$container = include __DIR__.'/container.php';

use Symfony\Component\Console\Application;
use Command\TranslateTimeCommand;
use Command\SearchCommand;
use Action\Find;
use Action\Transform;

$application = new Application();
$application->add(new TranslateTimeCommand(new Transform(), $container['descriptor_registry']));
$application->add(new SearchCommand(new Find(), $container['descriptor_registry']));
$application->run();