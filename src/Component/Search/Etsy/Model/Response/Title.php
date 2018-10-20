<?php

namespace App\Component\Search\Etsy\Model\Response;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class Title implements ArrayNotationInterface
{
    /**
     * @var int $truncationLimit
     */
    private $truncationLimit = 57;
    /**
     * @var string $truncated
     */
    private $truncated;
    /**
     * @var string $original
     */
    private $original;
    /**
     * Title constructor.
     * @param string $original
     */
    public function __construct(
        string $original
    ) {
        $this->original = $original;

        $this->truncated = (strlen($original) > $this->truncationLimit) ?
            substr($original, 0, $this->truncationLimit).'...' :
            $original;
    }
    /**
     * @return string
     */
    public function getTruncated(): string
    {
        return $this->truncated;
    }
    /**
     * @return string
     */
    public function getOriginal(): string
    {
        return $this->original;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'original' => $this->getOriginal(),
            'truncated' => $this->getTruncated(),
        ];
    }
}