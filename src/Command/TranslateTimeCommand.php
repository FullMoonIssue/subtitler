<?php

namespace Command;

use Action\Transform;
use Domain\Descriptor\DescriptorRegistry;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TranslateTimeCommand
 * @package Domain\Command
 */
class TranslateTimeCommand extends AbstractCommand
{
    const COMMAND = 'subtitler:translate-time';

    /**
     * @var Transform
     */
    private $transform;

    /**
     * TranslateTimeCommand constructor.
     * @param Transform $transform
     * @param DescriptorRegistry $descriptorRegistry
     */
    public function __construct(Transform $transform, DescriptorRegistry $descriptorRegistry)
    {
        $this->transform = $transform;

        parent::__construct(self::COMMAND, $descriptorRegistry);
    }

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        parent::configure();

        $this
            ->setDescription('Do a time translation')
            ->addOption('translate', null, InputOption::VALUE_REQUIRED, 'ex: -1ms (milli second) // +1s (second) // -1m (minute) // +1h (hour)')
            ->addOption('from', null, InputOption::VALUE_OPTIONAL, 'Translate id to begin the time translation', 1)
            ->addOption('to', null, InputOption::VALUE_OPTIONAL, 'Translate id to end the time translation');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->checkInputs();

        $to = $input->getOption('to');
        if (null !== $to) {
            $to = (int) $to;
        }

        $this->transform->translate(
            $this->descriptor->buildMatrix(file_get_contents($this->inputFile)),
            $input->getOption('translate'),
            (int) $input->getOption('from'),
            $to,
            ($outputFile = sprintf(self::OUTPUT_FILE_PATH, $input->getArgument('input-file')))
        );

        $this->displayResults($outputFile);
    }

    /**
     * @param $outputFile
     */
    private function displayResults($outputFile)
    {
        $this->io->success(sprintf('The time translation is done. Your new file is here : %s', $outputFile));

        exit(self::DONE_WITHOUT_ERROR);
    }
}
