<?php

namespace Tests\Command;

use Action\Probe;
use Action\Transform;
use Command\SearchCommand;
use Command\TranslateTimeCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\AbstractTestConfig;

/**
 * Class AbstractCommandTest
 * @package Tests\Command
 */
class AbstractCommandTest extends AbstractTestConfig
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
        self::$application->add(new SearchCommand(new Probe(), $container['descriptor_registry']));
    }

    /**
     * @group AbstractCommand
     */
    public function testInputFileNotFound()
    {
        $command = self::$application->find('subtitler:search');
        $commandTester = new CommandTester($command);
        $commandParameters = [
            'command'        => $command->getName(),
            'input-file'     => 'unexisting_file.srt',
            '--input-folder' => self::FIXTURES_INPUT_FOLDER
        ];

        $this->assertEquals(
            400,
            $commandTester->execute($commandParameters)
        );
    }

    /**
     * @group AbstractCommand
     */
    public function testExtensionFileNotHandled()
    {
        $command = self::$application->find('subtitler:search');
        $commandTester = new CommandTester($command);
        $commandParameters = [
            'command'        => $command->getName(),
            'input-file'     => 'fixtures.not',
            '--input-folder' => self::FIXTURES_INPUT_FOLDER
        ];

        $this->assertEquals(
            401,
            $commandTester->execute($commandParameters)
        );
    }
}
