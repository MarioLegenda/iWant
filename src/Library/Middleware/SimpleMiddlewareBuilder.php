<?php

namespace App\Library\Middleware;

use App\Library\Util\Util;

class SimpleMiddlewareBuilder
{
    /**
     * @var array $stack
     */
    private $stack = [];
    /**
     * @var null|array $globalParameters
     */
    private $globalParameters = null;
    /**
     * @param bool $newInstance
     * @param array|null $globalParameters
     * @return SimpleMiddlewareBuilder
     *
     * $newInstance NOT IMPLEMENTED
     */
    public static function instance(
        array $globalParameters = null,
        bool $newInstance = false
    ): SimpleMiddlewareBuilder {
        return new static($globalParameters);
    }
    /**
     * SimpleMiddlewareBuilder constructor.
     * @param array|null $globalParameters
     */
    private function __construct(
        array $globalParameters = null
    ) {
        $this->globalParameters = $globalParameters;
    }
    /**
     * @param MiddlewareEntryInterface $middlewareEntry
     * @return SimpleMiddlewareBuilder
     */
    public function add(MiddlewareEntryInterface $middlewareEntry): SimpleMiddlewareBuilder
    {
        $this->stack[] = $middlewareEntry;

        return $this;
    }
    /**
     * @return array
     */
    public function run(): array
    {
        $stackGen = Util::createGenerator($this->stack);

        /** @var MiddlewareResultInterface $previousResult */
        $previousResult = null;
        foreach ($stackGen as $entry) {
            /** @var MiddlewareEntryInterface $item */
            $item = $entry['item'];

            /** @var MiddlewareResultInterface $result */
            $previousResult = $item->handle($previousResult, $this->globalParameters);
        }

        if ($previousResult === null) {
            return [];
        }

        return $previousResult->getResult();
    }
}