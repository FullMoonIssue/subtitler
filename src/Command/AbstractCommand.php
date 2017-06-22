<?php
namespace Command;

use Domain\Descriptor\DescriptorInterface;
use Domain\Descriptor\DescriptorRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class AbstractCommand
 * @package Command
 */
class AbstractCommand extends Command
{
    const DEFAULT_INPUT_FOLDER_PATH = __DIR__.'/input';
    const DEFAULT_OUTPUT_FOLDER_PATH = __DIR__.'/output';

    const DONE_WITHOUT_ERROR = 0;
    const ERROR_INPUT_FILE_NOT_FOUND = 400;
    const ERROR_EXTENSION_NOT_HANDLED = 401;

    /**
     * @var SymfonyStyle
     */
    protected $io;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var DescriptorRegistry
     */
    protected $descriptorRegistry;

    /**
     * @var DescriptorInterface
     */
    protected $descriptor;

    /**
     * @var string
     */
    protected $inputFile;

    /**
     * @var string
     */
    protected $extension;

    /**
     * AbstractCommand constructor.
     * @param string $commandName
     * @param DescriptorRegistry $descriptorRegistry
     */
    public function __construct($commandName, DescriptorRegistry $descriptorRegistry)
    {
        $this->descriptorRegistry = $descriptorRegistry;

        parent::__construct($commandName);
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this
            ->addArgument(
                'input-file',
                InputArgument::REQUIRED,
                'The file name (have to be in the Command/input folder by default)'
            )
            ->addOption(
                'input-folder',
                null,
                InputOption::VALUE_REQUIRED,
                'Change the destination of the input folder',
                self::DEFAULT_INPUT_FOLDER_PATH
            )
            ->addOption(
                'output-folder',
                null,
                InputOption::VALUE_REQUIRED,
                'Change the destination of the output folder',
                self::DEFAULT_OUTPUT_FOLDER_PATH
            );
    }

    /**
     * @return int
     */
    protected function checkInputs()
    {
        $this->inputFile = sprintf(
            '%s/%s',
            rtrim($this->input->getOption('input-folder'), '/'),
            $this->input->getArgument('input-file')
        );
        if (!file_exists($this->inputFile)) {
            $this->io->error('Input file not found.');

            return self::ERROR_INPUT_FILE_NOT_FOUND;
        } else {
            $this->extension = pathinfo($this->inputFile)['extension'];
            if (null === ($this->descriptor = $this->descriptorRegistry->searchDescriptor($this->extension))) {
                $this->io->error(
                    sprintf(
                        'No descriptor found for the extension %s. Allowed : %s',
                        $this->extension,
                        implode(', ', $this->descriptorRegistry->getSupportedExtensions())
                    )
                );

                return self::ERROR_EXTENSION_NOT_HANDLED;
            }
        }

        return self::DONE_WITHOUT_ERROR;
    }
}
