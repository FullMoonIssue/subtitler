<?php

namespace Tests\Domain;

use Domain\Matrix;

/**
 * Class MatrixTest
 * @package Tests\Domain
 */
class MatrixTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Matrix
     */
    private $matrix;

    /**
     * @var string
     */
    private $originalFormattedMatrix = <<<BLOCK
1
00:00:28,480 --> 00:00:31,020
Sentence 1
Sentence 2

2
00:00:31,420 --> 00:00:34,259
Sentence 3
Sentence 4

3
00:00:41,420 --> 00:00:44,259
Sentence 5
Sentence 6

BLOCK;

    public function setUp()
    {
        $fixtureContent = file_get_contents(__DIR__.'/Fixtures/fixture.srt');
        $this->matrix = Matrix::parseMatrix($fixtureContent);
    }

    /**
     * @group Matrix
     */
    public function testCreateMatrix()
    {
        $this->assertEquals($this->originalFormattedMatrix, $this->matrix->getFormattedMatrix());
    }

    /**
     * @group Matrix
     */
    public function testParseMatrixCountBlocks()
    {
        $this->assertCount(3, $this->matrix->getBlocks());
    }

    /**
     * @group Matrix
     * @expectedException \Domain\Exception\MatrixException
     * @expectedExceptionMessage Your translation value is not correct
     */
    public function testWrongUserTimeTranslate()
    {
        $this->matrix->translate('wrong', 1);
    }

    /**
     * @group Matrix
     * @expectedException \Domain\Exception\MatrixException
     * @expectedExceptionMessage You can translate only from a positive id
     */
    public function testWrongFromIdTranslate()
    {
        $this->matrix->translate('+1h', -2);
    }

    /**
     * @group Matrix
     * @expectedException \Domain\Exception\MatrixException
     * @expectedExceptionMessage You can translate only to a positive id
     */
    public function testWrongToIdTranslate()
    {
        $this->matrix->translate('+1h', 2, -9);
    }

    /**
     * @group Matrix
     * @expectedException \Domain\Exception\MatrixException
     * @expectedExceptionMessage The ending id have to be greater than the beginning in translation time
     */
    public function testFromIdGreaterThanToIdTranslate()
    {
        $this->matrix->translate('+1h', 2, 1);
    }

    /**
     * @group Matrix
     */
    public function testOnlyOneBlockTranslateHappens()
    {
        // --- Translate hours

        $this->matrix->translate('+1h', 1, 1);

        $matrixFormatted = <<<BLOCK
1
01:00:28,480 --> 01:00:31,020
Sentence 1
Sentence 2

2
00:00:31,420 --> 00:00:34,259
Sentence 3
Sentence 4

3
00:00:41,420 --> 00:00:44,259
Sentence 5
Sentence 6

BLOCK;

        $this->assertEquals($matrixFormatted, $this->matrix->getFormattedMatrix());
        
        $this->matrix->translate('-1h', 1, 1);
        
        $this->assertEquals($this->originalFormattedMatrix, $this->matrix->getFormattedMatrix());

        // --- Translate minutes

        $this->matrix->translate('+1m', 1, 1);

        $matrixFormatted = <<<BLOCK
1
00:01:28,480 --> 00:01:31,020
Sentence 1
Sentence 2

2
00:00:31,420 --> 00:00:34,259
Sentence 3
Sentence 4

3
00:00:41,420 --> 00:00:44,259
Sentence 5
Sentence 6

BLOCK;

        $this->assertEquals($matrixFormatted, $this->matrix->getFormattedMatrix());

        $this->matrix->translate('-1m', 1, 1);

        $this->assertEquals($this->originalFormattedMatrix, $this->matrix->getFormattedMatrix());

        // --- Translate seconds

        $this->matrix->translate('+1s', 1, 1);

        $matrixFormatted = <<<BLOCK
1
00:00:29,480 --> 00:00:32,020
Sentence 1
Sentence 2

2
00:00:31,420 --> 00:00:34,259
Sentence 3
Sentence 4

3
00:00:41,420 --> 00:00:44,259
Sentence 5
Sentence 6

BLOCK;

        $this->assertEquals($matrixFormatted, $this->matrix->getFormattedMatrix());

        $this->matrix->translate('-1s', 1, 1);

        $this->assertEquals($this->originalFormattedMatrix, $this->matrix->getFormattedMatrix());

        // --- Translate milli seconds

        $this->matrix->translate('+1u', 1, 1);

        $matrixFormatted = <<<BLOCK
1
00:00:28,481 --> 00:00:31,021
Sentence 1
Sentence 2

2
00:00:31,420 --> 00:00:34,259
Sentence 3
Sentence 4

3
00:00:41,420 --> 00:00:44,259
Sentence 5
Sentence 6

BLOCK;

        $this->assertEquals($matrixFormatted, $this->matrix->getFormattedMatrix());

        $this->matrix->translate('-1u', 1, 1);

        $this->assertEquals($this->originalFormattedMatrix, $this->matrix->getFormattedMatrix());
    }

    /**
     * @group Matrix
     */
    public function testAllBlocksTranslateHappens()
    {
        // --- Translate hours

        $this->matrix->translate('+1h', 1);

        $matrixFormatted = <<<BLOCK
1
01:00:28,480 --> 01:00:31,020
Sentence 1
Sentence 2

2
01:00:31,420 --> 01:00:34,259
Sentence 3
Sentence 4

3
01:00:41,420 --> 01:00:44,259
Sentence 5
Sentence 6

BLOCK;

        $this->assertEquals($matrixFormatted, $this->matrix->getFormattedMatrix());

        $this->matrix->translate('-1h', 1);

        $this->assertEquals($this->originalFormattedMatrix, $this->matrix->getFormattedMatrix());

        // --- Translate minutes

        $this->matrix->translate('+1m', 1);

        $matrixFormatted = <<<BLOCK
1
00:01:28,480 --> 00:01:31,020
Sentence 1
Sentence 2

2
00:01:31,420 --> 00:01:34,259
Sentence 3
Sentence 4

3
00:01:41,420 --> 00:01:44,259
Sentence 5
Sentence 6

BLOCK;

        $this->assertEquals($matrixFormatted, $this->matrix->getFormattedMatrix());

        $this->matrix->translate('-1m', 1);

        $this->assertEquals($this->originalFormattedMatrix, $this->matrix->getFormattedMatrix());

        // --- Translate seconds

        $this->matrix->translate('+1s', 1);

        $matrixFormatted = <<<BLOCK
1
00:00:29,480 --> 00:00:32,020
Sentence 1
Sentence 2

2
00:00:32,420 --> 00:00:35,259
Sentence 3
Sentence 4

3
00:00:42,420 --> 00:00:45,259
Sentence 5
Sentence 6

BLOCK;

        $this->assertEquals($matrixFormatted, $this->matrix->getFormattedMatrix());

        $this->matrix->translate('-1s', 1);

        $this->assertEquals($this->originalFormattedMatrix, $this->matrix->getFormattedMatrix());

        // --- Translate milli seconds

        $this->matrix->translate('+1u', 1);

        $matrixFormatted = <<<BLOCK
1
00:00:28,481 --> 00:00:31,021
Sentence 1
Sentence 2

2
00:00:31,421 --> 00:00:34,260
Sentence 3
Sentence 4

3
00:00:41,421 --> 00:00:44,260
Sentence 5
Sentence 6

BLOCK;

        $this->assertEquals($matrixFormatted, $this->matrix->getFormattedMatrix());

        $this->matrix->translate('-1u', 1);

        $this->assertEquals($this->originalFormattedMatrix, $this->matrix->getFormattedMatrix());
    }
}