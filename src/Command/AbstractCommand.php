<?php
namespace Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class AbstractCommand
 * @package Command
 */
class AbstractCommand extends Command
{
    const INPUT_FILE_PATH = __DIR__.'/input/%s';
    const OUTPUT_FILE_PATH = __DIR__.'/output/%s';

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
     * @var array
     */
    protected $allowedExtensions = ['srt'];

    /**
     * @var string
     */
    protected $inputFile;

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
        $this->addArgument(
            'input-file',
            InputArgument::REQUIRED,
            'The file name (have to be in the Command/input folder)'
        );
    }

    protected function checkInputs()
    {
        $this->inputFile = sprintf(self::INPUT_FILE_PATH, $this->input->getArgument('input-file'));
        if(!file_exists($this->inputFile)) {
            $this->io->error('Input file not found.');
            exit(self::ERROR_INPUT_FILE_NOT_FOUND);
        } elseif(!in_array(($extension = pathinfo($this->inputFile)['extension']), $this->allowedExtensions, true)) {
            $this->io->error(
                sprintf(
                    'The extension of the input file %s is currently not handled. Here are the ones which are : [%s]',
                    $extension,
                    implode(', ', $this->allowedExtensions)
                )
            );
            exit(self::ERROR_EXTENSION_NOT_HANDLED);
        }
    }
}