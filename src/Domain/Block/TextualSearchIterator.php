<?php
namespace Domain\Block;

/**
 * Class TextualSearchIterator
 * @package Domain\Block
 */
class TextualSearchIterator extends \FilterIterator
{
    /**
     * @var string
     */
    private $text;

    /**
     * TextualSearchIterator constructor.
     * @param \Iterator $iterator
     * @param string $text
     */
    public function __construct(\Iterator $iterator, $text)
    {
        parent::__construct($iterator);

        $this->text = $text;
    }

    public function accept()
    {
        /** @var ProbableBlockInterface $probableBlock */
        $probableBlock = $this->getInnerIterator()->current();

        return $probableBlock->searchByText($this->text);
    }
}