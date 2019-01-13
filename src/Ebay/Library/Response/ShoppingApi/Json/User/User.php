<?php

namespace App\Ebay\Library\Response\ShoppingApi\Json\User;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\Util;

class User implements ArrayNotationInterface
{
    /**
     * @var string
     */
    private $userId;
    /**
     * @var bool
     */
    private $feedbackPrivate;
    /**
     * @var string
     */
    private $feedbackRatingStar;
    /**
     * @var int
     */
    private $feedbackScore;
    /**
     * @var bool
     */
    private $newUser;
    /**
     * @var string
     */
    private $registrationDate;
    /**
     * @var string
     */
    private $registrationSite;
    /**
     * @var string
     */
    private $status;
    /**
     * @var string
     */
    private $sellerBusinessType;
    /**
     * @var string|null
     */
    private $storeUrl;
    /**
     * @var string|null
     */
    private $storeName;
    /**
     * @var string
     */
    private $sellerItemsUrl;
    /**
     * @var string
     */
    private $aboutMeUrl;
    /**
     * @var string|null
     */
    private $myWorldUrl;
    /**
     * @var string|null
     */
    private $positiveFeedbackPercent;

    /**
     * User constructor.
     * @param string $userId
     * @param bool $feedbackPrivate
     * @param string $feedbackRatingStar
     * @param int $feedbackScore
     * @param bool $newUser
     * @param string $registrationDate
     * @param string $registrationSite
     * @param string $status
     * @param string $sellerBusinessType
     * @param string $storeUrl
     * @param string $storeName
     * @param string $sellerItemsUrl
     * @param string|null $aboutMeUrl
     * @param string $myWorldUrl
     * @param string $positiveFeedbackPercent
     */
    public function __construct(
        string $userId,
        bool $feedbackPrivate,
        string $feedbackRatingStar,
        int $feedbackScore,
        bool $newUser,
        string $registrationDate,
        string $registrationSite,
        string $status,
        string $sellerBusinessType,
        ?string $storeUrl,
        ?string $storeName,
        string $sellerItemsUrl,
        ?string $aboutMeUrl,
        ?string $myWorldUrl,
        ?string $positiveFeedbackPercent
    ) {
        $this->userId = $userId;
        $this->feedbackPrivate = $feedbackPrivate;
        $this->feedbackRatingStar = $feedbackRatingStar;
        $this->feedbackScore = $feedbackScore;
        $this->newUser = $newUser;
        $this->registrationDate = $registrationDate;
        $this->registrationSite = $registrationSite;
        $this->status = $status;
        $this->sellerBusinessType = $sellerBusinessType;
        $this->storeUrl = $storeUrl;
        $this->storeName = $storeName;
        $this->sellerItemsUrl = $sellerItemsUrl;
        $this->aboutMeUrl = $aboutMeUrl;
        $this->myWorldUrl = $myWorldUrl;
        $this->positiveFeedbackPercent = $positiveFeedbackPercent;
    }
    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }
    /**
     * @return bool
     */
    public function isFeedbackPrivate(): bool
    {
        return $this->feedbackPrivate;
    }
    /**
     * @return string
     */
    public function getFeedbackRatingStar(): string
    {
        return $this->feedbackRatingStar;
    }
    /**
     * @return int
     */
    public function getFeedbackScore(): int
    {
        return $this->feedbackScore;
    }
    /**
     * @return bool
     */
    public function isNewUser(): bool
    {
        return $this->newUser;
    }
    /**
     * @return string
     */
    public function getRegistrationDate(): string
    {
        return $this->registrationDate;
    }
    /**
     * @return string
     */
    public function getRegistrationSite(): string
    {
        return $this->registrationSite;
    }
    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
    /**
     * @return string
     */
    public function getSellerBusinessType(): string
    {
        return $this->sellerBusinessType;
    }
    /**
     * @return string|null
     */
    public function getStoreUrl(): ?string
    {
        return $this->storeUrl;
    }
    /**
     * @return string|null
     */
    public function getStoreName(): ?string
    {
        return $this->storeName;
    }
    /**
     * @return string
     */
    public function getSellerItemsUrl(): string
    {
        return $this->sellerItemsUrl;
    }
    /**
     * @return string|null
     */
    public function getAboutMeUrl(): ?string
    {
        return $this->aboutMeUrl;
    }
    /**
     * @return string|null
     */
    public function getMyWorldUrl(): ?string
    {
        return $this->myWorldUrl;
    }
    /**
     * @return string|null
     */
    public function getPositiveFeedbackPercent(): ?string
    {
        return $this->positiveFeedbackPercent;
    }
    /**
     * @return \DateTime
     */
    public function getRegistrationDateObject(): \DateTime
    {
        return Util::toDateTime($this->getRegistrationDate(), Util::getDateTimeApplicationFormat());
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {
        return [
            'userId' => $this->getUserId(),
            'feedbackPrivate' => $this->isFeedbackPrivate(),
            'feedbackRatingStar' => $this->getFeedbackRatingStar(),
            'feedbackScore' => $this->getFeedbackScore(),
            'newUser' => $this->isNewUser(),
            'registrationDate' => Util::formatFromDate($this->getRegistrationDateObject()),
            'registrationSite' => $this->getRegistrationSite(),
            'status' => $this->getStatus(),
            'sellerBusinessType' => $this->getSellerBusinessType(),
            'storeUrl' => $this->getStoreUrl(),
            'storeName' => $this->getStoreName(),
            'sellerItemsUrl' => $this->getSellerItemsUrl(),
            'aboutMeUrl' => $this->getAboutMeUrl(),
            'myWorldUrl' => $this->getMyWorldUrl(),
            'positiveFeedbackPercent' => $this->getPositiveFeedbackPercent(),
        ];
    }
}