<?php

namespace App\Ebay\Library\Response\FindingApi\Json\Result;

class SellerInfo
{
    /**
     * @var string
     */
    private $sellerUserName;
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
     * @param string $sellerUserName
     * @param string $feedbackScore
     * @param string $positiveFeedbackPercent
     * @param string $feedbackRatingStar
     * @param bool $topRatedSeller
     */
    public function __construct(
        string $sellerUserName,
        string $feedbackScore,
        string $positiveFeedbackPercent,
        string $feedbackRatingStar,
        bool $topRatedSeller
    ) {
        $this->sellerUserName = $sellerUserName;
        $this->feedbackScore = $feedbackScore;
        $this->positiveFeedbackPercent = $positiveFeedbackPercent;
        $this->feedbackRatingStar = $feedbackRatingStar;
        $this->topRatedSeller = $topRatedSeller;
    }
    /**
     * @return string
     */
    public function getSellerUserName(): string
    {
        return $this->sellerUserName;
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
     * @return bool
     */
    public function isTopRatedSeller(): bool
    {
        return $this->topRatedSeller;
    }
}