<?php
/**
 * Created by PhpStorm.
 * User: macbook
 * Date: 06/08/2018
 * Time: 11:40
 */

namespace App\Amazon\Source;


use App\Amazon\Source\Repository\ProductAdvertisingApiRepository;

class ProductAdvertisingApiSource
{
    /**
     * @var ProductAdvertisingApiRepository $productAdvertisingRepository
     */
    private $productAdvertisingRepository;
    /**
     * ProductAdvertisingApiSource constructor.
     * @param ProductAdvertisingApiRepository $productAdvertisingApiRepository
     */
    public function __construct(
        ProductAdvertisingApiRepository $productAdvertisingApiRepository
    ) {
        $this->productAdvertisingRepository = $productAdvertisingApiRepository;
    }
    /**
     * @param string $url
     * @return string
     */
    public function getProductAdvertisingResource(string $url): string
    {
        $this->productAdvertisingRepository->getResource($url);
    }
}