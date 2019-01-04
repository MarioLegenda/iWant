<?php

namespace App\Library\Exception;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class ExceptionBody implements ArrayNotationInterface
{
    /**
     * @var bool $parentCalled
     */
    private $parentCalled = false;
    /**
     * @var int $statusCode
     */
    private $statusCode;
    /**
     * @var array $body
     */
    protected $body = [];

    public function __construct(
        int $statusCode
    ) {
        $this->parentCalled = true;
        $this->statusCode = $statusCode;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        if (!$this->parentCalled) {
            $message = sprintf(
                '%s parent constructor not called. You have to call the parent constructor so the body could be constructed properly',
                get_class($this)
            );

            throw new \RuntimeException($message);
        }

        return $this->body;
    }
    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}