<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem;

class UserItem extends AbstractItem
{
    private $userId;

    private $feedbackRatingStar;

    private $feedbackScore;

    private $storeName;

    public function getUserId($default = null): string
    {
        if ($this->userId === null) {
            if (!empty($this->simpleXml->UserID)) {
                $this->setUserId((string) $this->simpleXml->UserID);
            }
        }

        if ($this->userId === null and $default !== null) {
            return $default;
        }

        return $this->userId;
    }

    public function getFeedbackRatingStar($default = null): string
    {
        if ($this->feedbackRatingStar === null) {
            if (!empty($this->simpleXml->FeedbackRatingStar)) {
                $this->setFeedbackRatingStar((string) $this->simpleXml->FeedbackRatingStar);
            }
        }

        if ($this->feedbackRatingStar === null and $default !== null) {
            return $default;
        }

        return $this->feedbackRatingStar;
    }

    public function getFeedbackScore($default = null): ?int
    {
        if ($this->feedbackScore === null) {
            if (!empty($this->simpleXml->FeedbackScore)) {
                $this->setFeedbackScore((int) $this->simpleXml->FeedbackScore);
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
    public function getStoreName($default = null): string
    {
        if ($this->storeName === null) {
            if (!empty($this->simpleXml->StoreName)) {
                $this->setStoreName((string) $this->simpleXml->StoreName);
            }
        }

        if ($this->storeName === null and $default !== null) {
            return $default;
        }

        return $this->storeName;
    }
    /**
     * @param string $storeName
     * @return $this
     */
    private function setStoreName(string $storeName)
    {
        $this->storeName = $storeName;

        return $this;
    }
    /**
     * @param int|null $feedbackScore
     * @return $this
     */
    private function setFeedbackScore(int $feedbackScore = null)
    {
        $this->feedbackScore = $feedbackScore;

        return $this;
    }
    /**
     * @param string $feedbackRatingStar
     * @return $this
     */
    private function setFeedbackRatingStar(string $feedbackRatingStar)
    {
        $this->feedbackRatingStar = $feedbackRatingStar;

        return $this;
    }
    /**
     * @param string $userId
     */
    private function setUserId(string $userId)
    {
        $this->userId = $userId;
    }
}