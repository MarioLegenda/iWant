<?php

namespace App\Ebay\Library\Response\ShoppingApi;

use App\Ebay\Library\Response\ResponseModelInterface;
use App\Ebay\Library\Response\ShoppingApi\Json\User\User;

interface GetUserProfileResponseInterface extends ResponseModelInterface
{
    /**
     * @return User
     */
    public function getUser(): User;
}