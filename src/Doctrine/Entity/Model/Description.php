<?php

namespace App\Doctrine\Entity\Model;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class Description implements ArrayNotationInterface
{
    /**
     * @var int $truncationLimit
     */
    private $truncationLimit = 150;
    /**
     * @var string $original
     */
    private $original;
    /**
     * @var string $truncated
     */
    private $truncated;
    /**
     * Description constructor.
     * @param string $original
     */
    public function __construct(string $original)
    {
        $this->original = $original;

        $this->truncated = (strlen($original) > $this->truncationLimit) ?
            substr($original, 0, $this->truncationLimit).'...' :
            $original;
    }
    /**
     * @return string
     */
    public function getOriginal(): string
    {
        return $this->original;
    }
    /**
     * @return string
     */
    public function getTruncated(): string
    {
        return $this->truncated;
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