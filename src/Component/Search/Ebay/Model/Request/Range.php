<?php

namespace App\Component\Search\Ebay\Model\Request;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class Range implements ArrayNotationInterface
{
    /**
     * @var int $from
     */
    private $from;
    /**
     * @var int $to
     */
    private $to;
    /**
     * Range constructor.
     * @param int $from
     * @param int $to
     */
    public function __construct(int $from, int $to)
    {
        $this->from = $from;
        $this->to = $to;
    }
    /**
     * @return int
     */
    public function getFrom(): int
    {
        return $this->from;
    }
    /**
     * @return int
     */
    public function getTo(): int
    {
        return $this->to;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'from' => $this->getFrom(),
            'to' => $this->getTo(),
        ];
    }
}