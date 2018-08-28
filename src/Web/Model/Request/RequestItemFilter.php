<?php

namespace App\Web\Model\Request;

use App\Library\Infrastructure\Type\TypeInterface;

class RequestItemFilter
{
    /**
     * @var TypeInterface $type
     */
    private $type;
    /**
     * @var iterable $data
     */
    private $data;
    /**
     * RequestItemFilter constructor.
     * @param TypeInterface $type
     * @param iterable $data
     */
    public function __construct(
        TypeInterface $type,
        iterable $data
    ) {
        $this->type = $type;
        $this->data = $data;
    }
    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface
    {
        return $this->type;
    }
    /**
     * @return iterable
     */
    public function getData(): iterable
    {
        return $this->data;
    }
}