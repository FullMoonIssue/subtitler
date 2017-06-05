<?php

namespace Tests\Action;

use Action\Find;

class FindTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group Find
     */
    public function testSearchInBetweenTimes()
    {
        $find = new Find();
        $founds = array_keys($find->search(__DIR__.'/../Fixtures/fixture.srt', null, '00:00:35,259'));
        $this->assertCount(2, $founds);
        $this->assertEquals(2, $founds[0]);
        $this->assertEquals(3, $founds[1]);
    }
}