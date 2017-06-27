<?php

namespace Command;

use Action\Probe;
use Domain\Descriptor\DescriptorRegistry;
use Domain\Time\TimeInterface;
use Symfony\Component\Console\Helper\Table;
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

    const ERROR_NO_TYPE_OF_SEARCH_ASSIGNED = 300;
    const ERROR_MULTIPLE_TYPE_OF_SEARCH_ASSIGNED = 301;

    /**
     * @var string
     */
    protected $searchByText;

    /**
     * @var TimeInterface
     */
    protected $searchByTime;

    /**
     * @var Probe
     */
    protected $probe;

    /**
     * SearchCommand constructor.
     * @param Probe $probe
     * @param DescriptorRegistry $descriptorRegistry
     */
    public function __construct(Probe $probe, DescriptorRegistry $descriptorRegistry)
    {
        $this->probe = $probe;

        parent::__construct(self::COMMAND, $descriptorRegistry);
    }

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        parent::configure();

        $this
            ->setDescription('Find the translate id of a block by a text or a time')
            ->addOption('by-text', null, InputOption::VALUE_OPTIONAL, 'Search by a providing text')
            ->addOption('by-time', null, InputOption::VALUE_OPTIONAL, 'Search by a providing time (ex: 00:00:30,420 for a .srt file)');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (self::DONE_WITHOUT_ERROR === ($errorCode = $this->checkInputs())) {
            $founds = $this->probe->search(
                $this->descriptor->buildMatrix(file_get_contents($this->inputFile)),
                $this->searchByText,
                $this->searchByTime
            );
            $this->displayResults($founds);

            return self::DONE_WITHOUT_ERROR;
        }

        return $errorCode;
    }

    /**
     * @return int
     */
    protected function checkInputs()
    {
        if (self::DONE_WITHOUT_ERROR !== ($errorCode = parent::checkInputs())) {
            return $errorCode;
        }

        $this->searchByText = $this->input->getOption('by-text');
        $this->searchByTime = $this->input->getOption('by-time');

        if (null === $this->searchByText && null === $this->searchByTime) {
            $this->io->error('You have to provide a text or a time to do your research.');
            return self::ERROR_NO_TYPE_OF_SEARCH_ASSIGNED;
        }

        if (null !== $this->searchByText && null !== $this->searchByTime) {
            $this->io->error('You have to provide either a text or a time to do your research.');
            return self::ERROR_MULTIPLE_TYPE_OF_SEARCH_ASSIGNED;
        }

        if (null !== $this->searchByTime) {
            $this->searchByTime = $this->descriptor->buildTime($this->searchByTime);
        }

        return self::DONE_WITHOUT_ERROR;
    }

    /**
     * @param array $founds
     */
    private function displayResults(array $founds)
    {
        if ($this->searchByTime) {
            $this->io->title(sprintf('Search by time : %s', $this->searchByTime->getFormattedTime()));
            if (1 === ($count = count($founds))) {
                $this->io->section('Exact block found');
            } elseif (1 < $count) {
                $this->io->section('In between block found');
            }
        } else {
            $this->io->title(sprintf('Search by text : %s', $this->searchByText));
        }

        if (0 === count($founds)) {
            $this->io->comment('No block found');
        } else {
            $table = new Table($this->output);
            $table
                ->setHeaders(['Block id', 'Block'])
                ->setRows(
                    array_map(
                        function ($id, $block) {
                            return [$id, $block];
                        },
                        array_keys($founds),
                        array_values($founds)
                    )
                )
                ->render();
        }
    }
}
