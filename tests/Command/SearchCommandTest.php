<?php

namespace Tests\Command;

use Action\Find;
use Action\Transform;
use Command\SearchCommand;
use Command\TranslateTimeCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class SearchCommandTest
 * @package Tests\Command
 */
class SearchCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Application
     */
    protected static $application;

    public static function setUpBeforeClass()
    {
        $container = include __DIR__ . '/../../container.php';
        self::$application = new Application();
        self::$application->add(new TranslateTimeCommand(new Transform(), $container['descriptor_registry']));
        self::$application->add(new SearchCommand(new Find(), $container['descriptor_registry']));
    }

//    /**
//     * @group SearchCommand
//     */
//    public function testTooManyArguments()
//    {
//        $command = self::$application->find('subtitler:search');
//        $commandTester = new CommandTester($command);
//        $commandParameters = [
//            'command'        => $command->getName(),
//            'input-file'     => 'fixture.srt',
//            '--input-folder' => __DIR__ . '/../Fixtures'
//        ];
//
//        $this->assertEquals(
//            300,
//            $commandTester->execute($commandParameters)
//        );
//
//        $this->assertEquals(
//            301,
//            $commandTester->execute(
//                array_merge(
//                    $commandParameters,
//                    [
//                        '--by-text' => 'Sentence',
//                        '--by-time' => '00:00:01,300'
//                    ]
//                )
//            )
//        );
//    }

    /**
     * @group SearchCommand
     */
    public function testSearchByText()
    {
        $command = self::$application->find('subtitler:search');
        $commandTester = new CommandTester($command);

        $commandParameters = [
            'command'        => $command->getName(),
            'input-file'     => 'fixture.srt',
            '--input-folder' => __DIR__ . '/../Fixtures'
        ];

        // --- Existing text

        $commandTester->execute(array_merge($commandParameters, ['--by-text' => 'Sentence']));

        $output = $commandTester->getDisplay();
        $expected = <<<DISPLAY
Search by text : Sentence
=========================

+----+-------------------------------+
| Id | Block                         |
+----+-------------------------------+
| 1  | 1                             |
|    | 00:00:28,480 --> 00:00:31,020 |
|    | Sentence 1                    |
|    | Sentence 2                    |
|    |                               |
| 2  | 2                             |
|    | 00:00:31,420 --> 00:00:34,259 |
|    | Sentence 3                    |
|    | Sentence 4                    |
|    |                               |
| 3  | 3                             |
|    | 00:00:41,420 --> 00:00:44,259 |
|    | Sentence 5                    |
|    | Sentence 6                    |
|    |                               |
+----+-------------------------------+
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
            'command'        => $command->getName(),
            'input-file'     => 'fixture.srt',
            '--by-time'      => '00:00:01,300',
            '--input-folder' => __DIR__ . '/../Fixtures'
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