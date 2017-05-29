<?php

namespace Command;

use Domain\Matrix;
use Symfony\Component\Console\Input\InputArgument;
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
    const INPUT_FILE = __DIR__.'/input/%s';
    const OUTPUT_FILE = __DIR__.'/output/%s';
    const ERROR_INPUT_FILE_NOT_FOUND = 1;

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this
            ->setName(self::COMMAND)
            ->setDescription('Do a time translation')
            ->addArgument('file', InputArgument::REQUIRED, 'The file name (have to be in the Command/input folder) and will have the same name in the Command/output folder')
            ->addOption('translate', null, InputOption::VALUE_REQUIRED, 'ex: -1u (milli second) // +1s (second) // -1m (minute) // +1h (hour)')
            ->addOption('from', null, InputOption::VALUE_OPTIONAL, 'Translate id to begin the time translation', 1)
            ->addOption('to', null, InputOption::VALUE_OPTIONAL, 'Translate id to end the time translation');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $file = sprintf(self::INPUT_FILE, $input->getArgument('file'));
        if(!file_exists($file)) {
            $this->io->error('Input file not found.');
            exit(self::ERROR_INPUT_FILE_NOT_FOUND);
        }

        $to = $input->getOption('to');
        if(null !== $to) {
            $to = (int) $to;
        }

        $matrix = Matrix::parseMatrix(file_get_contents($file));
        $matrix->translate($input->getOption('translate'), (int) $input->getOption('from'), $to);
        file_put_contents(sprintf(self::OUTPUT_FILE, $input->getArgument('file')), $matrix->getFormattedMatrix());

        $this->io->success('Done');
    }
}