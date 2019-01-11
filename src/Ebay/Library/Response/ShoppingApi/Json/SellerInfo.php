<?php

namespace App\Ebay\Library\Response\ShoppingApi\Json;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class SellerInfo implements ArrayNotationInterface
{
    /**
     * @var string
     */
    private $sellerId;
    /**
     * @var string
     */
    private $feedbackScore;
    /**
     * @var string
     */
    private $positiveFeedbackPercent;
    /**
     * @var string
     */
    private $feedbackRatingStar;
    /**
     * @var bool
     */
    private $topRatedSeller;
    /**
     * SellerInfo constructor.
     * @param string $sellerId
     * @param string $feedbackScore
     * @param string $positiveFeedbackPercent
     * @param string $feedbackRatingStar
     * @param bool|null $topRatedSeller
     */
    public function __construct(
        string $sellerId,
        string $feedbackScore,
        string $positiveFeedbackPercent,
        string $feedbackRatingStar,
        ?bool $topRatedSeller
    ) {
        $this->sellerId = $sellerId;
        $this->feedbackScore = $feedbackScore;
        $this->positiveFeedbackPercent = $positiveFeedbackPercent;
        $this->feedbackRatingStar = $feedbackRatingStar;
        $this->topRatedSeller = $topRatedSeller;
    }
    /**
     * @return string
     */
    public function getSellerId(): string
    {
        return $this->sellerId;
    }
    /**
     * @return string
     */
    public function getFeedbackScore(): string
    {
        return $this->feedbackScore;
    }
    /**
     * @return string
     */
    public function getPositiveFeedbackPercent(): string
    {
        return $this->positiveFeedbackPercent;
    }
    /**
     * @return string
     */
    public function getFeedbackRatingStar(): string
    {
        return $this->feedbackRatingStar;
    }
    /**
     * @return bool|null
     */
    public function isTopRatedSeller(): ?bool
    {
        return $this->topRatedSeller;
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'sellerId' => $this->getSellerId(),
            'feedbackScore' => $this->getFeedbackScore(),
            'positiveFeedbackPercent' => $this->getPositiveFeedbackPercent(),
            'feedbackRatingStar' => $this->getFeedbackRatingStar(),
            'topRatedSeller' => $this->isTopRatedSeller(),
        ];
    }
}