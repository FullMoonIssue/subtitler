<?php

namespace Tests\Domain;

use Domain\Block;

/**
 * Class BlockTest
 * @package Tests\Domain
 */
class BlockTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Block
     */
    private $block;

    public function setUp()
    {
        $timeBegin = $this
            ->getMockBuilder('Domain\Time')
            ->disableOriginalConstructor()
            ->getMock();
        $timeBegin
            ->method('getTime')
            ->willReturn(new \DateTime('1970-01-01 00:46:58.740'));
        $timeBegin
            ->method('getFormattedTime')
            ->willReturn('00:46:58,740');

        $timeEnd = $this
            ->getMockBuilder('Domain\Time')
            ->disableOriginalConstructor()
            ->getMock();
        $timeEnd
            ->method('getTime')
            ->willReturn(new \DateTime('1970-01-01 00:47:01.299'));
        $timeEnd
            ->method('getFormattedTime')
            ->willReturn('00:47:01,299');

        $this->block = new Block($timeBegin, $timeEnd, ['Sentence 1.'], 709);
    }

    /**
     * @group Block
     */
    public function testRenderBlock()
    {
        $blockFormatted = <<<BLOCK
709
00:46:58,740 --> 00:47:01,299
Sentence 1.

BLOCK;

        $this->assertEquals($blockFormatted, $this->block->getFormattedBlock());
    }

    /**
     * @group Block
     */
    public function testParseBlock()
    {
        $blockFormatted = <<<BLOCK
709
00:46:58,740 --> 00:47:01,299
Sentence 1.
Sentence 2.
BLOCK;

        $block = Block::parseBlock($blockFormatted);
        $this->assertEquals(709, $block->getId());
        $this->assertEquals('00:46:58,740', $block->getTimeBegin()->getFormattedTime());
        $this->assertEquals('00:47:01,299', $block->getTimeEnd()->getFormattedTime());
        $this->assertEquals(
            [
                'Sentence 1.',
                'Sentence 2.'
            ],
            $block->getLines()
        );
        $this->assertNotEquals(130, $block->getId());
        $this->assertNotEquals('12:46:58,740', $block->getTimeBegin()->getFormattedTime());
        $this->assertNotEquals('12:47:01,299', $block->getTimeEnd()->getFormattedTime());
        $this->assertNotEquals(
            [
                'Wrong sentence'
            ],
            $block->getLines()
        );
    }

    /**
     * @group Block
     */
    public function testSearchById()
    {
        $this->assertTrue($this->block->searchById(709));
        $this->assertFalse($this->block->searchById(130));
    }

    /**
     * @group Block
     */
    public function testSearchByTime()
    {
        $this->assertTrue($this->block->searchByTime('00:46:58,741'));
        $this->assertFalse($this->block->searchByTime('00:46:57,739'));
        $this->assertFalse($this->block->searchByTime('00:47:01,300'));
    }

    /**
     * @group Block
     */
    public function testSearchByText()
    {
        $this->assertTrue($this->block->searchByText('sentence'));
        $this->assertFalse($this->block->searchByText('wrong'));
    }
}