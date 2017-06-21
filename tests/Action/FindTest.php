<?php

namespace Tests\Action;

use Action\Find;
use Domain\SubRip\Matrix;
use Domain\SubRip\Time;
use Domain\TimeInterface;

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
     * @param $beforeFirstTime
     * @param $exactlyFirstTime
     * @param $justAfterFirstTime
     * @param $afterLastTime
     */
    public function testSearchDifferentTimes(
        $file,
        TimeInterface $beforeFirstTime,
        TimeInterface $exactlyFirstTime,
        TimeInterface $justAfterFirstTime,
        TimeInterface $afterLastTime
    )
    {
        $find = new Find();
        $matrix = Matrix::parseMatrix(file_get_contents($file));

        $founds = array_keys($find->search($matrix, null, $beforeFirstTime));
        $this->assertCount(0, $founds);

        $founds = array_keys($find->search($matrix, null, $exactlyFirstTime));
        $this->assertCount(1, $founds);
        $this->assertEquals(1, $founds[0]);

        $founds = array_keys($find->search($matrix, null, $justAfterFirstTime));
        $this->assertCount(2, $founds);
        $this->assertEquals(1, $founds[0]);
        $this->assertEquals(2, $founds[1]);

        $founds = array_keys($find->search($matrix, null, $afterLastTime));
        $this->assertCount(0, $founds);
    }

    /**
     * @return array
     */
    public function getFixtures()
    {
        return [
            [
                __DIR__.'/../Fixtures/fixture.srt',
                new Time('00:00:25,480'),
                new Time('00:00:29,480'),
                new Time('00:00:31,300'),
                new Time('00:01:40,259')
            ]
        ];
    }
}
