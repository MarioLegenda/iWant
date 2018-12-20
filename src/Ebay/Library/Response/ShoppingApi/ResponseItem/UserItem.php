<?php

namespace App\Ebay\Library\Response\ShoppingApi\ResponseItem;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\Util;

class UserItem extends AbstractItem implements ArrayNotationInterface
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
     * @var string $storeName
     */
    private $storeName;
    /**
     * @var boolean $newUser
     */
    private $newUser;
    /**
     * @var \DateTime $registrationDate
     */
    private $registrationDate;
    /**
     * @var string $status
     */
    private $status;
    /**
     * @var string $sellerBusinessType
     */
    private $sellerBusinessType;
    /**
     * @param null $default
     * @return string
     */
    public function getSellerBusinessType($default = null): string
    {
        if ($this->sellerBusinessType === null) {
            if (!empty($this->simpleXml->SellerBusinessType)) {
                $this->sellerBusinessType= (string) $this->simpleXml->SellerBusinessType;
            }
        }

        if ($this->sellerBusinessType === null and $default !== null) {
            return $default;
        }

        return $this->sellerBusinessType;
    }
    /**
     * @param null $default
     * @return string
     */
    public function getStatus($default = null): string
    {
        if ($this->status === null) {
            if (!empty($this->simpleXml->Status)) {
                $this->status = (string) $this->simpleXml->Status;
            }
        }

        if ($this->status === null and $default !== null) {
            return $default;
        }

        return $this->status;
    }
    /**
     * @param null $default
     * @return bool
     */
    public function isNewUser($default = null): bool
    {
        if ($this->newUser === null) {
            if (!empty($this->simpleXml->NewUser)) {
                $this->setNewUser((string) $this->simpleXml->NewUser);
            }
        }

        if ($this->newUser === null and $default !== null) {
            return $default;
        }

        return $this->newUser;
    }
    /**
     * @return \DateTime
     */
    public function getRegistrationDate(): \DateTime
    {
        if ($this->registrationDate === null) {
            if (!empty($this->simpleXml->RegistrationDate)) {
                $this->setRegistrationDate((string) $this->simpleXml->RegistrationDate);
            }
        }

        if ($this->registrationDate === null) {
            return null;
        }

        return $this->registrationDate;
    }
    /**
     * @param null $default
     * @return string
     */
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
    /**
     * @param null $default
     * @return string
     */
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
    /**
     * @param null $default
     * @return int|null
     */
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
    /**
     * @param string $newUser
     */
    private function setNewUser(string $newUser)
    {
        $isNewUser = ($newUser === 'true') ? true : false;

        $this->newUser = $isNewUser;
    }
    /**
     * @param string $date
     */
    public function setRegistrationDate(string $date)
    {
        $this->registrationDate = Util::toDateTime($date);
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'userId' => $this->getUserId(),
            'storeName' => $this->getStoreName(),
            'feedbackRatingStar' => $this->getFeedbackRatingStar(),
            'feedbackScore' => $this->getFeedbackScore(),
            'newUser' => $this->isNewUser(),
            'registrationDate' => Util::formatFromDate($this->getRegistrationDate()),
            'status' => $this->getStatus(),
            'sellerBusinessType' => $this->getSellerBusinessType(),
        ];
    }
}