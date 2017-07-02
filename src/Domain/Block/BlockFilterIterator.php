<?php

namespace Domain\Block;

/**
 * Class BlockFilterIterator
 */
class BlockFilterIterator extends \FilterIterator
{
    /**
     * {@inheritdoc}
     */
    public function accept()
    {
        return !empty($this->getInnerIterator()->current());
    }
}
