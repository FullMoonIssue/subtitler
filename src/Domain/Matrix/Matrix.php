<?php
namespace Domain\Matrix;

use Domain\Block\BlockInterface;
use Domain\Exception\MatrixException;

/**
 * Class Matrix
 * @package Domain
 */
abstract class Matrix implements MatrixInterface
{
    /**
     * @var BlockInterface[]
     */
    private $blocks;

    /**
     * Matrix constructor.
     * @param BlockInterface[] $blocks
     */
    public function __construct(array $blocks)
    {
        $this->blocks = $blocks;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormattedMatrix()
    {
        return implode(
            "\n",
            array_map(
                function (BlockInterface $block) {
                    return $block->getFormattedBlock();
                },
                $this->blocks
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function translate($userTime, $fromId = 1, $toId = null)
    {
        if (!preg_match('=(?P<operation>[\+|-])(?P<count>\d+)(?P<unit>ms|[h|m|s])=', $userTime, $matches)) {
            throw new MatrixException('Your translation value is not correct');
        }
        if ($fromId < 1) {
            throw new MatrixException('You can translate only from a positive id');
        }
        if (null !== $toId && $toId < 1) {
            throw new MatrixException('You can translate only to a positive id');
        }
        if (null !== $toId && $toId < $fromId) {
            throw new MatrixException('The ending id have to be greater than the beginning in translation time');
        }

        $addOperation = ('+' == $matches['operation']);
        $count = (int) $matches['count'];
        $unit = $matches['unit'];
        $method = null;
        switch ($unit) {
            case 'h':
                $method = ($addOperation ? 'addHours' : 'subtractHours');
                break;
            case 'm':
                $method = ($addOperation ? 'addMinutes' : 'subtractMinutes');
                break;
            case 's':
                $method = ($addOperation ? 'addSeconds' : 'subtractSeconds');
                break;
            case 'ms':
                $method = ($addOperation ? 'addMilliSeconds' : 'subtractMilliSeconds');
                break;
        }

        $startTranslate = false;
        foreach ($this->blocks as $block) {
            if ($block->searchById($fromId)) {
                $startTranslate = true;
            }
            if ($startTranslate) {
                $block->getTimeBegin()->$method($count);
                $block->getTimeEnd()->$method($count);
            }
            if (null !== $toId && $block->searchById($toId)) {
                break;
            }
        }
    }
}
