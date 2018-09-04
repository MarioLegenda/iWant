<?php

namespace App\Ebay\Library\Response\ShoppingApi;

use App\Ebay\Library\Response\ShoppingApi\ResponseItem\Categories;

interface GetCategoryInfoResponseInterface
{
    /**
     * @return Categories
     */
    public function getCategories(): Categories;
}