<?php

namespace App\Component\Search\Ebay\Model\Response;

use App\Ebay\Library\Response\FindingApi\Json\Result\SellerInfo;
use App\Ebay\Library\Response\FindingApi\Json\Result\StoreInfo;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class BusinessEntity implements ArrayNotationInterface
{
    /**
     * @var bool $isDataIncomplete
     */
    private $isDataIncomplete = true;
    /**
     * @var string|null $storeName
     */
    private $storeName;
    /**
     * @var string|null $storeUrl
     */
    private $storeUrl;
    /**
     * @var string|null $sellerUserName
     */
    private $sellerUserName;
    /**
     * @var int|null $feedbackScore
     */
    private $feedbackScore;
    /**
     * @var bool $positiveFeedbackPercent
     */
    private $positiveFeedbackPercent;
    /**
     * @var string|null $feedbackRatingStar
     */
    private $feedbackRatingStar;
    /**
     * @var bool $topRatedSeller
     */
    private $topRatedSeller;
    /**
     * BusinessEntity constructor.
     * @param StoreInfo|null $storeInfo
     * @param SellerInfo|null $sellerInfo
     */
    public function __construct(
        ?StoreInfo $storeInfo,
        ?SellerInfo $sellerInfo
    ) {
        if (!$storeInfo instanceof StoreInfo and !$sellerInfo instanceof SellerInfo) {
            return;
        }

        if ($storeInfo instanceof StoreInfo) {
            $this->storeName = $storeInfo->getStoreName();
            $this->storeUrl = $storeInfo->getStoreURL();

            $this->isDataIncomplete = false;
        }

        if ($sellerInfo instanceof SellerInfo) {
            $this->sellerUserName = $sellerInfo->getSellerUsername();
            $this->positiveFeedbackPercent = $sellerInfo->getPositiveFeedbackPercent();
            $this->feedbackScore = $sellerInfo->getFeedbackScore();
            $this->feedbackRatingStar = $sellerInfo->getFeedbackRatingStar();
            $this->topRatedSeller = stringToBool($sellerInfo->isTopRatedSeller(), true);

            $this->isDataIncomplete = false;
        }
    }
    /**
     * @return bool
     */
    public function isDataIncomplete(): bool
    {
        return $this->isDataIncomplete;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'isDataIncomplete' => $this->isDataIncomplete(),
            'storeName' => $this->storeName,
            'storeUrl' => $this->storeUrl,
            'sellerUsername' => $this->sellerUserName,
            'feedbackScore' => $this->feedbackScore,
            'positiveFeedbackPercent' => $this->positiveFeedbackPercent,
            'feedbackRatingStar' => $this->feedbackRatingStar,
            'topRatedSeller' => $this->topRatedSeller,
        ];
    }
}