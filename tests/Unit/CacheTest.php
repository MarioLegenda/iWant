<?php

namespace App\Tests\Unit;

use App\Bonanza\Business\Finder as BonanzaFinder;
use App\Ebay\Business\Finder as EbayFinder;
use App\Etsy\Business\Finder as EtsyFinder;
use App\Bonanza\Presentation\Model\BonanzaApiModel;
use App\Cache\Cache\ApiRequestCache;
use App\Cache\Implementation\RequestCacheImplementation;
use App\Doctrine\Entity\RequestCache;
use App\Doctrine\Repository\RequestCacheRepository;
use App\Library\Util\Util;
use App\Tests\Bonanza\DataProvider\DataProvider as BonanzaDataProvider;
use App\Tests\Ebay\FindingApi\DataProvider\DataProvider as EbayFindingApiProvider;
use App\Tests\Etsy\DataProvider\DataProvider as EtsyDataProvider;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel as FindingApiModel;
use App\Etsy\Presentation\Model\EtsyApiModel;
use App\Tests\Library\BasicSetup;

class CacheTest extends BasicSetup
{
    public function setUp()
    {
        parent::setUp();

        $this->locator->get(ApiRequestCache::class)->clear();
    }

    public function test_bonanza_cache()
    {
        /** @var RequestCacheImplementation $cacheImplementation */
        $cacheImplementation = $this->locator->get(RequestCacheImplementation::class);
        /** @var RequestCacheRepository $requestCacheRepository */
        $requestCacheRepository = $this->locator->get(RequestCacheRepository::class);
        /** @var BonanzaDataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.bonanza_api');
        /** @var BonanzaApiModel $bonanzaApiModel */
        $bonanzaApiModel = $dataProvider->getFindItemsByKeywordsData(['boots', 'mountain']);
        /** @var BonanzaFinder $bonanzaFinder */
        $bonanzaFinder = $this->locator->get(BonanzaFinder::class);

        $bonanzaFinder->search($bonanzaApiModel);

        $allCaches = $requestCacheRepository->findAll();

        static::assertEquals(1, count($allCaches));

        /** @var RequestCache $cache */
        $cache = $allCaches[0];
        $expiresAt = $cache->getExpiresAt();

        /**
         *  Set expire time in 1 second
         */
        $newTtl = Util::toDateTime()->getTimestamp() + 1;
        $cache->setExpiresAt($newTtl);
        $requestCacheRepository->getManager()->persist($cache);
        $requestCacheRepository->getManager()->flush();

        sleep(2);

        $bonanzaFinder->search($bonanzaApiModel);

        $allCaches = $requestCacheRepository->findAll();

        static::assertEquals(1, count($allCaches));

        /** @var RequestCache $cache */
        $cache = $allCaches[0];
        $newExpiresAt = $cache->getExpiresAt();

        static::assertNotEquals($expiresAt, $newExpiresAt);
        static::assertGreaterThan($newExpiresAt, $expiresAt);

        $this->locator->get(ApiRequestCache::class)->clear();
    }

    public function test_ebay_cache()
    {
        /** @var RequestCacheImplementation $cacheImplementation */
        $cacheImplementation = $this->locator->get(RequestCacheImplementation::class);
        /** @var RequestCacheRepository $requestCacheRepository */
        $requestCacheRepository = $this->locator->get(RequestCacheRepository::class);
        /** @var EbayFindingApiProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.finding_api');
        /** @var FindingApiModel $findingApiModel */
        $findingApiModel = $dataProvider->getFindItemsByKeywordsData('boots for mountain');
        /** @var EbayFinder $bonanzaFinder */
        $ebayFinder = $this->locator->get(EbayFinder::class);

        $ebayFinder->findItemsByKeywords($findingApiModel);

        $allCaches = $requestCacheRepository->findAll();

        static::assertEquals(1, count($allCaches));

        /** @var RequestCache $cache */
        $cache = $allCaches[0];
        $expiresAt = $cache->getExpiresAt();

        /**
         *  Set expire time in 1 second
         */
        $newTtl = Util::toDateTime()->getTimestamp() + 1;
        $cache->setExpiresAt($newTtl);
        $requestCacheRepository->getManager()->persist($cache);
        $requestCacheRepository->getManager()->flush();

        sleep(2);

        $ebayFinder->findItemsByKeywords($findingApiModel);

        $allCaches = $requestCacheRepository->findAll();

        static::assertEquals(1, count($allCaches));

        /** @var RequestCache $cache */
        $cache = $allCaches[0];
        $newExpiresAt = $cache->getExpiresAt();

        static::assertNotEquals($expiresAt, $newExpiresAt);
        static::assertGreaterThan($newExpiresAt, $expiresAt);

        $this->locator->get(ApiRequestCache::class)->clear();
    }

    public function test_etsy_cache()
    {
        /** @var RequestCacheImplementation $cacheImplementation */
        $cacheImplementation = $this->locator->get(RequestCacheImplementation::class);
        /** @var RequestCacheRepository $requestCacheRepository */
        $requestCacheRepository = $this->locator->get(RequestCacheRepository::class);
        /** @var EtsyDataProvider $dataProvider */
        $dataProvider = $this->locator->get('data_provider.etsy_api');
        /** @var EtsyApiModel $etsyApiModel */
        $etsyApiModel = $dataProvider->getEtsyApiModel();
        /** @var EtsyFinder $etsyFinder */
        $etsyFinder = $this->locator->get(EtsyFinder::class);

        $etsyFinder->search($etsyApiModel);

        $allCaches = $requestCacheRepository->findAll();

        static::assertEquals(1, count($allCaches));

        /** @var RequestCache $cache */
        $cache = $allCaches[0];
        $expiresAt = $cache->getExpiresAt();

        /**
         *  Set expire time in 1 second
         */
        $newTtl = Util::toDateTime()->getTimestamp() + 1;
        $cache->setExpiresAt($newTtl);
        $requestCacheRepository->getManager()->persist($cache);
        $requestCacheRepository->getManager()->flush();

        sleep(2);

        $etsyFinder->search($etsyApiModel);

        $allCaches = $requestCacheRepository->findAll();

        static::assertEquals(1, count($allCaches));

        /** @var RequestCache $cache */
        $cache = $allCaches[0];
        $newExpiresAt = $cache->getExpiresAt();

        static::assertNotEquals($expiresAt, $newExpiresAt);
        static::assertGreaterThan($newExpiresAt, $expiresAt);

        $this->locator->get(ApiRequestCache::class)->clear();
    }
}