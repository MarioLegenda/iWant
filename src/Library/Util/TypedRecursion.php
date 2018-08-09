<?php

namespace App\Library\Util;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class TypedRecursion
{
    const RESPECT_ARRAY_NOTATION = 1;
    const DO_NOT_RESPECT_ARRAY_NOTATION = 2;
    const THROW_EXCEPTION_ON_NON_ARRAY_NOTATION = 4;
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
     * @param int $mode
     * @return iterable
     */
    public function iterate(int $mode = TypedRecursion::RESPECT_ARRAY_NOTATION): iterable
    {
        $array = [];
        foreach ($this->data as $key => $item) {
            $key = $item['key'];
            $entry = $item['item'];

            if (is_object($entry)) {
                switch ($mode) {
                    case TypedRecursion::THROW_EXCEPTION_ON_NON_ARRAY_NOTATION:
                        $message = sprintf(
                            '%s provides recursive iteration only for objects that implement %s',
                            get_class($this),
                            ArrayNotationInterface::class
                        );

                        throw new \RuntimeException($message);
                    case TypedRecursion::RESPECT_ARRAY_NOTATION:
                        if ($entry instanceof ArrayNotationInterface) {
                            $array[$key] = $entry->toArray();

                            continue;
                        }

                        break;
                    case TypedRecursion::DO_NOT_RESPECT_ARRAY_NOTATION:
                        $array[$key] = $entry;
                }

                continue;
            }

            if (is_array($entry)) {
                $typedRecursion = new TypedRecursion($item);

                $array[$key] = $typedRecursion->iterate();

                continue;
            }

            $array[$key] = $entry;
        }

        return $array;
    }
}