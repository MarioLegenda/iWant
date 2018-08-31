<?php

namespace App\Ebay\Library\Response\ShoppingApi;

use App\Ebay\Library\Response\ShoppingApi\ResponseItem\UserItem;

interface GetUserProfileResponseInterface
{
    /**
     * @return UserItem
     */
    public function getUser(): UserItem;
}