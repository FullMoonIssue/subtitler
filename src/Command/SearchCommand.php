<?php

namespace Command;

use Domain\Matrix;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SearchCommand
 * @package Domain\Command
 */
class SearchCommand extends AbstractCommand
{
    const COMMAND = 'subtitler:search';
    const INPUT_FILE = __DIR__.'/input/%s';
    const ERROR_NO_TYPE_OF_SEARCH_ASSIGNED = 1;
    const ERROR_MULTIPLE_TYPE_OF_SEARCH_ASSIGNED = 2;
    const ERROR_INPUT_FILE_NOT_FOUND = 3;

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this
            ->setName(self::COMMAND)
            ->setDescription('Find the translate id of a block by a text or a time')
            ->addArgument('file', InputArgument::REQUIRED, 'The file name (have to be in the Command/input folder)')
            ->addOption('by-text', null, InputOption::VALUE_OPTIONAL, 'Search by a providing text')
            ->addOption('by-time', null, InputOption::VALUE_OPTIONAL, 'Search by a providing time (ex: 00:00:30,420)');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $text = $input->getOption('by-text');
        $time = $input->getOption('by-time');

        if(null === $text && null === $time) {
            $this->io->error('You have to provide a text or a time to do your research.');
            exit(self::ERROR_NO_TYPE_OF_SEARCH_ASSIGNED);
        }

        if(null !== $text && null !== $time) {
            $this->io->error('You have to provide either a text or a time to do your research.');
            exit(self::ERROR_MULTIPLE_TYPE_OF_SEARCH_ASSIGNED);
        }

        $founds = [];
        $file = sprintf(self::INPUT_FILE, $input->getArgument('file'));
        if(!file_exists($file)) {
            $this->io->error('Input file not found.');
            exit(self::ERROR_INPUT_FILE_NOT_FOUND);
        }

        $matrix = Matrix::parseMatrix(file_get_contents($file));
        foreach($matrix->getBlocks() as $block) {
            if (null !== $text) {
                if ($block->searchByText($text)) {
                    $founds[$block->getId()] = $block->getFormattedBlock();
                }
            } else {
                if ($block->searchByTime($time)) {
                    $founds[$block->getId()] = $block->getFormattedBlock();
                }
            }
        }

        if(0 === sizeof($founds)) {
            $this->io->comment('Nothing found');
        } else {
            $this->io->title('Results found');
            $table = new Table($output);
            $table
                ->setHeaders(['Translate id', 'Block'])
                ->setRows(
                    array_map(
                        function($id, $block) { return [$id, $block]; },
                        array_keys($founds),
                        array_values($founds)
                    )
                )
                ->render();
        }
    }
}