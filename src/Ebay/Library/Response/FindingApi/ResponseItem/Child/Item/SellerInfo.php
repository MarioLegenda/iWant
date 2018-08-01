<?php

namespace App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\AbstractItem;

class SellerInfo extends AbstractItem implements ArrayNotationInterface
{
    /**
     * @var string $feedbackRatingStar
     */
    private $feedbackRatingStar;
    /**
     * @var int $feedbackScore
     */
    private $feedbackScore;
    /**
     * @var float $positiveFeedbackPercent
     */
    private $positiveFeedbackPercent;
    /**
     * @var string $sellerUserName
     */
    private $sellerUsername;
    /**
     * @var bool $topRatedSeller
     */
    private $topRatedSeller;
    /**
     * @param null $default
     * @return null|string
     */
    public function getFeedbackRatingStar($default = null)
    {
        if ($this->feedbackRatingStar === null) {
            if (!empty($this->simpleXml->feedbackRatingStar)) {
                $this->setFeedbackRatingStar((string) $this->simpleXml->feedbackRatingStar);
            }
        }

        if ($this->feedbackRatingStar === null and $default !== null) {
            return $default;
        }

        return $this->feedbackRatingStar;
    }
    /**
     * @param null $default
     * @return int|null
     */
    public function getFeedbackScore($default = null)
    {
        if ($this->feedbackScore === null) {
            if (!empty($this->simpleXml->feedbackScore)) {
                $this->setFeedbackScore((int) $this->simpleXml->feedbackScore);
            }
        }

        if ($this->feedbackScore === null and $default !== null) {
            return $default;
        }

        return $this->feedbackScore;
    }
    /**
     * @param null $default
     * @return float|null
     */
    public function getPositiveFeedbackPercent($default = null)
    {
        if ($this->positiveFeedbackPercent === null) {
            if (!empty($this->simpleXml->positiveFeedbackPercent)) {
                $this->setPositiveFeedbackPercent((float) $this->simpleXml->positiveFeedbackPercent);
            }
        }

        if ($this->positiveFeedbackPercent === null and $default !== null) {
            return $default;
        }

        return $this->positiveFeedbackPercent;
    }
    /**
     * @param null $default
     * @return null|string
     */
    public function getSellerUsername($default = null)
    {
        if ($this->sellerUsername === null) {
            if (!empty($this->simpleXml->sellerUserName)) {
                $this->setSellerUsername((string) $this->simpleXml->sellerUserName);
            }
        }

        if ($this->sellerUsername === null and $default !== null) {
            return $default;
        }

        return $this->sellerUsername;
    }
    /**
     * @param null $default
     * @return bool|null
     */
    public function getTopRatedSeller($default = null)
    {
        if ($this->topRatedSeller === null) {
            if (!empty($this->simpleXml->topRatedSeller)) {
                $this->setTopRatedSeller((bool) $this->simpleXml->topRatedSeller);
            }
        }

        if ($this->topRatedSeller === null and $default !== null) {
            return $default;
        }

        return $this->topRatedSeller;
    }
    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            'topRatedSeller' => $this->getTopRatedSeller(),
            'sellerUsername' => $this->getSellerUsername(),
            'feedbackScore' => $this->getFeedbackScore(),
            'positiveFeedbackPercent' => $this->getPositiveFeedbackPercent(),
            'feedbackRatingStar' => $this->getFeedbackRatingStar(),
        );
    }

    private function setSellerUsername(string $sellerUsername) : SellerInfo
    {
        $this->sellerUsername = $sellerUsername;

        return $this;
    }

    private function setPositiveFeedbackPercent(float $positiveFeedbackPercent) : SellerInfo
    {
        $this->positiveFeedbackPercent = $positiveFeedbackPercent;

        return $this;
    }

    private function setFeedbackScore(int $feedbackScore) : SellerInfo
    {
        $this->feedbackScore = $feedbackScore;

        return $this;
    }

    private function setFeedbackRatingStar(string $feedbackRatingStart) : SellerInfo
    {
        $this->feedbackRatingStar = $feedbackRatingStart;

        return $this;
    }

    private function setTopRatedSeller(bool $topRatedSeller) : SellerInfo
    {
        $this->topRatedSeller = $topRatedSeller;

        return $this;
    }
}