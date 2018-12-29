<?php

namespace App\Doctrine\Entity;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\MarketplaceType;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use App\Library\Util\Util;

/**
 * @Entity @Table(
 *     name="single_product_item"
 * )
 * @HasLifecycleCallbacks()
 **/
class SingleProductItem extends BaseUniqueIdentifierCache implements ArrayNotationInterface
{
    /**
     * @var int $id
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    private $id;
    /**
     * @var MarketplaceType $marketplace
     * @Column(type="string")
     */
    private $marketplace;
    /**
     * SingleProductItem constructor.
     * @param string $itemId
     * @param string $response
     * @param MarketplaceType $marketplace
     * @param int $expiresAt
     */
    public function __construct(
        string $itemId,
        string $response,
        MarketplaceType $marketplace,
        int $expiresAt
    ) {
        parent::__construct($itemId, $response);

        $this->marketplace = (string) $marketplace;
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