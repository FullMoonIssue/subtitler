<?php

namespace Tests\Action;

use Action\Probe;
use Domain\Matrix\MatrixInterface;
use Domain\SubRip\Matrix as SubRipMatrix;
use Domain\SubRip\Time as SubRipTime;
use Domain\Time\TimeInterface;
use Tests\AbstractTestConfig;

/**
 * Class ProbeTest
 */
class ProbeTest extends AbstractTestConfig
{
    /**
     * @dataProvider getFixtures
     * @group Probe
     * @group ProbeFix
     *
     * @param MatrixInterface $matrix
     * @param $beforeFirstTime
     * @param $exactlyFirstTime
     * @param $betweenTwoTimes
     * @param $afterLastTime
     */
    public function testSearchDifferentTimes(
        MatrixInterface $matrix,
        TimeInterface $beforeFirstTime,
        TimeInterface $exactlyFirstTime,
        TimeInterface $betweenTwoTimes,
        TimeInterface $afterLastTime
    ) {
        $probe = new Probe();

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
                SubRipMatrix::parseMatrix(file_get_contents(self::FIXTURES_SUBRIP_FULL_PATH)),
                new SubRipTime('00:00:25,480'),
                new SubRipTime('00:00:29,480'),
                new SubRipTime('00:00:35,000'),
                new SubRipTime('00:01:40,259'),
            ],
        ];
    }
}
