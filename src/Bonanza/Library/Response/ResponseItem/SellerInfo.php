<?php

namespace App\Bonanza\Library\Response\ResponseItem;

use App\Library\Infrastructure\Notation\ArrayNotationInterface;

class SellerInfo implements ArrayNotationInterface
{
    /**
     * @var iterable $response
     */
    private $response;
    /**
     * SellerInfo constructor.
     * @param iterable $response
     */
    public function __construct(iterable $response)
    {
        $this->response = $response;
    }

    public function getFeedbackRatingStar(): ?string
    {
        return $this->response['feedbackRatingStar'];
    }
    /**
     * @return null|string
     */
    public function getPositiveFeedbackPercent(): ?string
    {
        return sprintf(
            '%s%',
            $this->response['positiveFeedbackPercent']
        );
    }
    /**
     * @return string
     */
    public function getSellerUserName(): string
    {
        return $this->response['sellerUserName'];
    }
    /**
     * @return bool
     */
    public function getAvailableForChat(): bool
    {
        $availableForChat = $this->response['availableForChat'];

        if (is_bool($availableForChat)) {
            return $availableForChat;
        }

        return ($availableForChat === 'true') ? true : false;
    }
    /**
     * @return null|string
     */
    public function getUserPicture(): ?string
    {
        return $this->response['userPicture'];
    }
    /**
     * @return iterable
     */
    public function toArray(): iterable
    {

    }
}