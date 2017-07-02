<?php

namespace Tests\Action;

use Action\Probe;
use Domain\SubRip\Matrix;
use Domain\SubRip\Time;
use Domain\Time\TimeInterface;
use Tests\AbstractTestConfig;

/**
 * Class ProbeTest
 * @package Tests\Action
 */
class ProbeTest extends AbstractTestConfig
{
    /**
     * @dataProvider getFixtures
     * @group Probe
     * @group ProbeFix
     *
     * @param string $file
     * @param $beforeFirstTime
     * @param $exactlyFirstTime
     * @param $betweenTwoTimes
     * @param $afterLastTime
     */
    public function testSearchDifferentTimes(
        $file,
        TimeInterface $beforeFirstTime,
        TimeInterface $exactlyFirstTime,
        TimeInterface $betweenTwoTimes,
        TimeInterface $afterLastTime
    ) {
        $probe = new Probe();
        $matrix = Matrix::parseMatrix(file_get_contents($file));

        $founds = array_keys($probe->search($matrix, null, $beforeFirstTime));
        $this->assertCount(0, $founds);

        $founds = array_keys($probe->search($matrix, null, $exactlyFirstTime));
        $this->assertCount(1, $founds);
        $this->assertEquals(1, $founds[0]);

        $founds = array_keys($probe->search($matrix, null, $betweenTwoTimes));
        $this->assertCount(2, $founds);
        $this->assertEquals(2, $founds[0]);
        $this->assertEquals(3, $founds[1]);

        $founds = array_keys($probe->search($matrix, null, $afterLastTime));
        $this->assertCount(0, $founds);
    }

    /**
     * @return array
     */
    public function getFixtures()
    {
        return [
            [
                self::FIXTURES_FULL_PATH,
                new Time('00:00:25,480'),
                new Time('00:00:29,480'),
                new Time('00:00:35,000'),
                new Time('00:01:40,259')
            ]
        ];
    }
}
