#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Command\TranslateTimeCommand;
use Command\SearchCommand;

$application = new Application();
$application->add(new TranslateTimeCommand());
$application->add(new SearchCommand());
$application->run();