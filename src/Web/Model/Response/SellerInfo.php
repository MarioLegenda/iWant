<?php

namespace App\Web\Model\Response;

use App\Library\Infrastructure\Type\TypeInterface;
use App\Web\Model\Response\Type\DeferrableType;

class SellerInfo implements DeferrableHttpDataObjectInterface
{
    /**
     * @var string $userName
     */
    private $userName;
    /**
     * SellerInfo constructor.
     * @param string $userName
     */
    public function __construct(
        string $userName
    ) {
        $this->userName = $userName;
    }
    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }
    /**
     * @return iterable
     */
    public function getDeferrableData(): iterable
    {
        $message = sprintf(
            '%s already has all necessary data and does not have to be deferred therefor, %s::getDeferrableData() cannot be used',
            get_class($this),
            get_class($this)
        );

        throw new \RuntimeException($message);
    }
    /**
     * @return TypeInterface
     */
    public function getDeferrableType(): TypeInterface
    {
        return DeferrableType::fromValue('concrete_object');
    }
}