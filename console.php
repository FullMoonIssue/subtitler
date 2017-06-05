#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Command\TranslateTimeCommand;
use Command\SearchCommand;
use Action\Search;
use Action\Transform;

$application = new Application();
$application->add(new TranslateTimeCommand(new Transform()));
$application->add(new SearchCommand(new Search()));
$application->run();