<?php

namespace App\Library\Util;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class TypedRecursion
{
    /**
     * @var iterable $data
     */
    private $data;
    /**
     * TypedRecursion constructor.
     * @param iterable $data
     */
    public function __construct(iterable $data)
    {
        if (!$data instanceof \Generator) {
            $data = Util::createGenerator($data);
        }

        $this->data = $data;
    }
    /**
     * @return iterable
     */
    public function iterate(): iterable
    {
        $array = [];
        foreach ($this->data as $key => $item) {
            $key = $item['key'];
            $item = $item['item'];

            if (is_object($item)) {
                if ($item instanceof ArrayNotationInterface) {
                    $array[$key] = $item->toArray();

                    continue;
                }
            }

            if (is_array($item)) {
                $typedRecursion = new TypedRecursion($item);

                $array[$key] = $typedRecursion->iterate();
            }

            $array[$key] = $item;
        }

        return $array;
    }
}