<?php

namespace App\Doctrine\Entity;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use App\Library\Util\Util;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @Entity @Table(
 *     name="shipping_costs_item",
 *     uniqueConstraints={ @UniqueConstraint(columns={"item_id"}) }
 * )
 * @HasLifecycleCallbacks()
 **/
class ShippingCostsItem extends BaseUniqueIdentifierCache implements ArrayNotationInterface
{
    /**
     * @var int $id
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;
    /**
     * SingleProductItem constructor.
     * @param string $itemId
     * @param string $response
     * @param int $expiresAt
     */
    public function __construct(
        string $itemId,
        string $response,
        int $expiresAt
    ) {
        parent::__construct($itemId, $response);

        $this->expiresAt = $expiresAt;
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'itemId' => $this->getItemId(),
            'response' => json_decode($this->getResponse(), true),
            'createdAt' => Util::formatFromDate($this->getCreatedAt()),
            'updatedAt' => Util::formatFromDate($this->getUpdatedAt()),
        ];
    }
}