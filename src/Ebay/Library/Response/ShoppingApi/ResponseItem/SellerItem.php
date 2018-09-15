<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem;

class SellerItem extends AbstractItem
{
    /**
     * @var string $userId
     */
    private $userId;
    /**
     * @var string $feedbackRatingStar
     */
    private $feedbackRatingStar;
    /**
     * @var string $feedbackScore
     */
    private $feedbackScore;
    /**
     * @var string $positiveFeedbackPercent
     */
    private $positiveFeedbackPercent;
    /**
     * @param null $default
     * @return string
     */
    public function getUserId($default = null): ?string
    {
        if ($this->userId === null) {
            if (!empty($this->simpleXml->UserID)) {
                $this->userId = (string) $this->simpleXml->UserID;
            }
        }

        if ($this->userId === null and $default !== null) {
            return $default;
        }

        return $this->userId;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getFeedbackRatingStart($default = null): ?string
    {
        if ($this->feedbackRatingStar === null) {
            if (!empty($this->simpleXml->FeedbackRatingStar)) {
                $this->feedbackRatingStar = (string) $this->simpleXml->FeedbackRatingStar;
            }
        }

        if ($this->feedbackRatingStar === null and $default !== null) {
            return $default;
        }

        return $this->feedbackRatingStar;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getFeedbackScore($default = null): ?string
    {
        if ($this->feedbackScore === null) {
            if (!empty($this->simpleXml->FeedbackScore)) {
                $this->feedbackScore = (string) $this->simpleXml->FeedbackScore;
            }
        }

        if ($this->feedbackScore === null and $default !== null) {
            return $default;
        }

        return $this->feedbackScore;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getPositiveFeedbackPercent($default = null): ?string
    {
        if ($this->positiveFeedbackPercent === null) {
            if (!empty($this->simpleXml->PositiveFeedbackPercent)) {
                $this->positiveFeedbackPercent = (string) $this->simpleXml->PositiveFeedbackPercent;
            }
        }

        if ($this->positiveFeedbackPercent === null and $default !== null) {
            return $default;
        }

        return $this->positiveFeedbackPercent;
    }
}