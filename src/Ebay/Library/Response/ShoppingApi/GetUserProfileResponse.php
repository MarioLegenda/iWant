<?php

namespace App\Ebay\Library\Response\ShoppingApi;

use App\Ebay\Library\Response\ShoppingApi\Json\Root;
use App\Ebay\Library\Response\ShoppingApi\Json\User\User;
use App\Library\Infrastructure\Notation\ArrayNotationInterface;
use App\Library\Util\Util;
use App\Ebay\Library\Response\ShoppingApi\Json\Error;

class GetUserProfileResponse implements GetUserProfileResponseInterface, ArrayNotationInterface
{
    /**
     * @var array
     */
    private $errors;
    /**
     * @var array
     */
    private $user;
    /**
     * @var array
     */
    private $root;
    /**
     * @var array
     */
    private $response;
    /**
     * GetUserProfileResponse constructor.
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }
    /**
     * @return Root
     */
    public function getRoot(): Root
    {
        if ($this->root instanceof Root) {
            return $this->root;
        }

        $this->root = new Root(
            $this->response['Ack'],
            $this->response['Timestamp'],
            $this->response['Version']
        );

        unset($this->response['Ack']);
        unset($this->response['Timestamp']);
        unset($this->response['Version']);

        return $this->root;
    }
    /**
     * @return User
     */
    public function getUser(): User
    {
        if ($this->user instanceof User) {
            return $this->user;
        }

        $userResponse = $this->response['User'];

        $this->user = new User(
            $userResponse['UserID'],
            stringToBool($userResponse['FeedbackPrivate']),
            $userResponse['FeedbackRatingStar'],
            (int) $userResponse['FeedbackScore'],
            stringToBool($userResponse['NewUser']),
            $userResponse['RegistrationDate'],
            $userResponse['RegistrationSite'],
            $userResponse['Status'],
            $userResponse['SellerBusinessType'],
            get_value_or_null($userResponse, 'StoreURL'),
            get_value_or_null($userResponse, 'StoreName'),
            $userResponse['SellerItemsURL'],
            get_value_or_null($userResponse, 'AboutMeURL'),
            get_value_or_null($userResponse, 'MyWorldURL'),
            get_value_or_null($userResponse, 'PositiveFeedbackPercent')
        );

        unset($this->response['User']);

        return $this->user;
    }
    /**
     * @param mixed $default
     * @return array
     */
    public function getErrors($default = null)
    {
        $errors = Util::createGenerator($this->response['Errors']);

        foreach ($errors as $entry) {
            $item = $entry['item'];

            $this->errors[] = new Error(
                $item['ShortMessage'],
                $item['LongMessage'],
                $item['ErrorCode'],
                $item['SeverityCode'],
                $item['ErrorClassification']
            );
        }

        unset($this->response['Errors']);

        return $this->errors;
    }

    /**
     * @return array
     */
    public function toArray(): iterable
    {
        return [
            'root' => $this->getRoot()->toArray(),
            'user' => $this->getUser()->toArray()
        ];
    }
}