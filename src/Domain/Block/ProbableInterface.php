<?php
namespace Domain\Block;

use Domain\Time\TimeInterface;

/**
 * Interface ProbableInterface
 * @package Domain
 */
interface ProbableInterface
{
    /**
     * @param int $id
     * @return bool
     */
    public function searchById($id);

    /**
     * @param string $text
     * @return bool
     */
    public function searchByText($text);

    /**
     * @param TimeInterface $time
     * @return bool
     */
    public function searchByTime(TimeInterface $time);
}
