<?php

namespace App\Web\Model\Type;

class TypeMap
{
    /**
     * @var TypeMap $instance
     */
    private static $instance;
    /**
     * @var iterable $map
     */
    private $map = [];

    public static function instance(): TypeMap
    {
        static::$instance = (static::$instance instanceof static) ? static::$instance : new static();

        return static::$instance;
    }
    /**
     * TypeMap constructor.
     */
    private function __construct()
    {
        $this->map = [
            LowestPrice::getUnConstructedValue() => LowestPrice::class,
            HighQuality::getUnConstructedValue() => HighQuality::class,
            HighestPrice::getUnConstructedValue() => HighestPrice::class,
            Handmade::getUnConstructedValue() => Handmade::class,
            PriceRange::getUnConstructedValue() => PriceRange::class,
            ShipsToCountry::getUnConstructedValue() => ShipsToCountry::class,
            Used::getUnConstructedValue() => Used::class,
        ];
    }
    /**
     * @param string $type
     * @return string
     */
    public function getTypeMapFor(string $type): string
    {
        if (!array_key_exists($type, $this->map)) {
            $message = sprintf(
                '%s does not exist in %s',
                $type,
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        return $this->map[$type];
    }
}