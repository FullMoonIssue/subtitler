<?php
namespace Domain;

/**
 * Interface BlockInterface
 * @package Domain
 */
interface BlockInterface
{
    /**
     * @param int $numberBlock
     * @param string $text
     * @return mixed
     */
    public static function parseBlock($numberBlock, $text);

    /**
     * @return string
     */
    public function getFormattedBlock();

    /**
     * @return int
     */
    public function getId();

    /**
     * @return TimeInterface
     */
    public function getTimeBegin();

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
