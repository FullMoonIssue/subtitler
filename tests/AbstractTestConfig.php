<?php

namespace Tests;

class AbstractTestConfig extends \PHPUnit_Framework_TestCase
{
    const FIXTURES_INPUT_FOLDER = __DIR__.'/Fixtures/input';
    const FIXTURES_FILE_NAME = 'fixtures.srt';
    const FIXTURES_FULL_PATH = self::FIXTURES_INPUT_FOLDER.'/'.self::FIXTURES_FILE_NAME;
    const FIXTURES_OUTPUT_FOLDER = __DIR__.'/Fixtures/output';
}
