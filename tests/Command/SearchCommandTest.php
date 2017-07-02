<?php

namespace Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class SearchCommandTest
 */
class SearchCommandTest extends AbstractCommandTest
{
    /**
     * @group SearchCommand
     */
    public function testWrongCountOfArguments()
    {
        $command = self::$application->find('subtitler:search');
        $commandTester = new CommandTester($command);
        $commandParameters = [
            'command' => $command->getName(),
            'input-file' => self::FIXTURES_FILE_NAME,
            '--input-folder' => self::FIXTURES_INPUT_FOLDER,
        ];

        $this->assertEquals(
            300,
            $commandTester->execute($commandParameters)
        );

        $this->assertEquals(
            301,
            $commandTester->execute(
                array_merge(
                    $commandParameters,
                    [
                        '--by-text' => 'Sentence',
                        '--by-time' => '00:00:01,300',
                    ]
                )
            )
        );
    }

    /**
     * @group SearchCommand
     */
    public function testSearchByText()
    {
        $command = self::$application->find('subtitler:search');
        $commandTester = new CommandTester($command);

        $commandParameters = [
            'command' => $command->getName(),
            'input-file' => self::FIXTURES_FILE_NAME,
            '--input-folder' => self::FIXTURES_INPUT_FOLDER,
        ];

        // --- Existing text

        $commandTester->execute(array_merge($commandParameters, ['--by-text' => 'Sentence']));

        $output = $commandTester->getDisplay();
        $expected = <<<DISPLAY
Search by text : Sentence
=========================

+----------+-------------------------------+
| Block id | Block                         |
+----------+-------------------------------+
| 1        | 1                             |
|          | 00:00:28,480 --> 00:00:31,020 |
|          | Sentence 1                    |
|          | Sentence 2                    |
|          |                               |
| 2        | 2                             |
|          | 00:00:31,420 --> 00:00:34,259 |
|          | Sentence 3                    |
|          | Sentence 4                    |
|          |                               |
| 3        | 3                             |
|          | 00:00:41,420 --> 00:00:44,259 |
|          | Sentence 5                    |
|          | Sentence 6                    |
|          |                               |
+----------+-------------------------------+
DISPLAY;

        $this->assertEquals(
            $expected,
            trim($output)
        );

        // --- Text not in the subtitles

        $commandTester->execute(array_merge($commandParameters, ['--by-text' => 'NoNoNo']));

        $output = $commandTester->getDisplay();
        $expected = <<<DISPLAY
Search by text : NoNoNo
=======================

 // No block found
DISPLAY;

        $this->assertEquals(
            $expected,
            trim($output)
        );
    }

    /**
     * @group SearchCommand
     */
    public function testSearchByTime()
    {
        $command = self::$application->find('subtitler:search');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'input-file' => self::FIXTURES_FILE_NAME,
            '--input-folder' => self::FIXTURES_INPUT_FOLDER,
            '--by-time' => '00:00:01,300',
        ]);

        $output = $commandTester->getDisplay();
        $expected = <<<DISPLAY
Search by time : 00:00:01,300
=============================

 // No block found
DISPLAY;

        $this->assertEquals(
            $expected,
            trim($output)
        );
    }
}
