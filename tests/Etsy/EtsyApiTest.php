<?php

namespace App\Tests\Etsy;

use App\Etsy\Library\Response\EtsyApiResponseModelInterface;
use App\Etsy\Library\Response\ResponseItem\Result;
use App\Etsy\Library\Response\ResponseItem\Results;
use App\Etsy\Library\Response\ResponseItem\ResultsInterface;
use App\Etsy\Library\Response\ShippingProfileEntriesResponseModel;
use App\Etsy\Presentation\EntryPoint\EtsyApiEntryPoint;
use App\Etsy\Presentation\Model\EtsyApiModel;
use App\Tests\Etsy\DataProvider\DataProvider;
use App\Tests\Library\BasicSetup;
use App\Web\Factory\EtsyResponseModelFactory;

class EtsyApiTest extends BasicSetup
{
    public function test_find_all_listing_active()
    {
        $etsyApiEntryPoint = $this->locator->get(EtsyApiEntryPoint::class);

        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.etsy_api');

        /** @var EtsyApiResponseModelInterface $responseModel */
        $responseModel = $etsyApiEntryPoint->findAllListingActive($dataProvider->getEtsyApiModel());

        static::assertNotEmpty($responseModel->getCount());
        static::assertNotEmpty($responseModel->getResults());
        static::assertInstanceOf(ResultsInterface::class, $responseModel->getResults());

        $results = $responseModel->getResults();

        /** @var Result $result */
        foreach ($results as $result) {
            static::assertInternalType('int', $result->getListingId());
            static::assertInternalType('string', $result->getTitle());
            static::assertInternalType('string', $result->getPrice());
            static::assertInternalType('string', $result->getDescription());
            static::assertInternalType('array', $result->getTaxonomyPath());
            static::assertInternalType('int', $result->getTaxonomyId());

            if (is_null($result->getShippingTemplateId())) {
                static::assertNull($result->getShippingTemplateId());
            } else {
                static::assertInternalType('int', $result->getShippingTemplateId());
            }

            static::assertInternalType('int', $result->getViews());
            static::assertInternalType('string', $result->getUrl());
            static::assertInternalType('array', $result->getTags());
            static::assertInternalType('array', $result->getSku());
            static::assertInternalType('int', $result->getQuantity());
            static::assertInternalType('string', $result->getCurrency());
            static::assertInternalType('string', $result->getState());
        }
    }

    public function test_find_all_listing_active_paginated()
    {
        $etsyApiEntryPoint = $this->locator->get(EtsyApiEntryPoint::class);

        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.etsy_api');

        $limits = range(1, 20);

        foreach ($limits as $limit) {
            /** @var EtsyApiResponseModelInterface $responseModel */
            $responseModel = $etsyApiEntryPoint->findAllListingActive($dataProvider->getEtsyApiModelWithLimit($limit));

            static::assertNotEmpty($responseModel->getCount());
            static::assertNotEmpty($responseModel->getResults());
            static::assertInstanceOf(ResultsInterface::class, $responseModel->getResults());

            $results = $responseModel->getResults();

            /** @var Result $result */
            foreach ($results as $result) {
                static::assertInternalType('int', $result->getListingId());
                static::assertInternalType('string', $result->getTitle());
                static::assertInternalType('string', $result->getPrice());
                static::assertInternalType('string', $result->getDescription());
                static::assertInternalType('array', $result->getTaxonomyPath());
                static::assertInternalType('int', $result->getTaxonomyId());

                if (is_null($result->getShippingTemplateId())) {
                    static::assertNull($result->getShippingTemplateId());
                } else {
                    static::assertInternalType('int', $result->getShippingTemplateId());
                }

                static::assertInternalType('int', $result->getViews());
                static::assertInternalType('string', $result->getUrl());
                static::assertInternalType('array', $result->getTags());
                static::assertInternalType('array', $result->getSku());
                static::assertInternalType('int', $result->getQuantity());
                static::assertInternalType('string', $result->getCurrency());
                static::assertInternalType('string', $result->getState());
            }
        }
    }

    public function test_all_shop_listings_featured()
    {
        /** @var EtsyApiEntryPoint $etsyApiEntryPoint */
        $etsyApiEntryPoint = $this->locator->get(EtsyApiEntryPoint::class);

        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.etsy_api');

        /** @var EtsyApiResponseModelInterface $responseModel */
        $responseModel = $etsyApiEntryPoint->findAllShopListingsFeatured($dataProvider->getEtsyApiModel());

        static::assertNotEmpty($responseModel->getCount());
        static::assertNotEmpty($responseModel->getResults());
        static::assertInstanceOf(ResultsInterface::class, $responseModel->getResults());

        $results = $responseModel->getResults();

        /** @var Result $result */
        foreach ($results as $result) {
            static::assertInternalType('int', $result->getListingId());
            static::assertInternalType('string', $result->getTitle());
            static::assertInternalType('string', $result->getPrice());
            static::assertInternalType('string', $result->getDescription());
            static::assertInternalType('array', $result->getTaxonomyPath());
            static::assertInternalType('int', $result->getTaxonomyId());

            if (is_null($result->getShippingTemplateId())) {
                static::assertNull($result->getShippingTemplateId());
            } else {
                static::assertInternalType('int', $result->getShippingTemplateId());
            }

            static::assertInternalType('int', $result->getViews());
            static::assertInternalType('string', $result->getUrl());
            static::assertInternalType('array', $result->getTags());
            static::assertInternalType('array', $result->getSku());
            static::assertInternalType('int', $result->getQuantity());
            static::assertInternalType('string', $result->getCurrency());
            static::assertInternalType('string', $result->getState());
        }
    }

    public function test_get_listing()
    {
        /** @var EtsyApiEntryPoint $etsyApiEntryPoint */
        $etsyApiEntryPoint = $this->locator->get(EtsyApiEntryPoint::class);

        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.etsy_api');

        $model = $dataProvider->getEtsyGetListingModel('550212552');

        $responseModel = $etsyApiEntryPoint->getListing($model);

        static::assertInstanceOf(EtsyApiResponseModelInterface::class, $responseModel);
    }

    public function test_find_all_listing_shipping_profile_entries()
    {
        /** @var EtsyApiEntryPoint $etsyApiEntryPoint */
        $etsyApiEntryPoint = $this->locator->get(EtsyApiEntryPoint::class);
        /** @var DataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.etsy_api');
        /** @var EtsyApiModel $model */
        $model = $dataProvider->getEtsyFindAllListingShippingProfileEntries('475764969');

        /** @var ShippingProfileEntriesResponseModel $responseModel */
        $responseModel = $etsyApiEntryPoint->findAllListingShippingProfileEntries($model);

        static::assertInstanceOf(ShippingProfileEntriesResponseModel::class, $responseModel);
        static::assertNotEmpty($responseModel->getCount());
        static::assertNotEmpty($responseModel->getResults());
        static::assertInstanceOf(ResultsInterface::class, $responseModel->getResults());
    }
}