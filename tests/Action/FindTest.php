<?php

namespace Tests\Action;

use Action\Find;
use Domain\SubRip\Matrix;
use Domain\SubRip\Time;

/**
 * Class FindTest
 * @package Tests\Action
 */
class FindTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getFixtures
     * @group Find
     *
     * @param string $file
     * @param string $time
     */
    public function testSearchInBetweenTimes($file, $time)
    {
        $find = new Find();
        $founds = array_keys($find->search(Matrix::parseMatrix(file_get_contents($file)), null, $time));
        $this->assertCount(2, $founds);
        $this->assertEquals(2, $founds[0]);
        $this->assertEquals(3, $founds[1]);
    }

    /**
     * @return array
     */
    public function getFixtures()
    {
        return [
            [
                __DIR__.'/../Fixtures/fixture.srt',
                new Time('00:00:35,259')
            ]
        ];
    }
}
