<?php

namespace App\Symfony\Command;

use App\Doctrine\Entity\ApplicationShop;
use App\Doctrine\Repository\ApplicationShopRepository;
use App\Doctrine\Repository\NormalizedCategoryRepository;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Ebay\Library\ItemFilter\ItemFilter;
use App\Ebay\Library\Response\ShoppingApi\GetUserProfileResponse;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\UserItem;
use App\Ebay\Presentation\Model\Query;
use App\Ebay\Presentation\ShoppingApi\EntryPoint\ShoppingApiEntryPoint;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\MarketplaceType;
use App\Library\Util\Util;
use App\Library\Representation\ShopsRepresentation;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Ebay\Presentation\ShoppingApi\Model\GetUserProfile;
use App\Ebay\Presentation\ShoppingApi\Model\ShoppingApiModel;

class CreateNativeShops extends BaseCommand
{
    /**
     * @var ShoppingApiEntryPoint $shoppingApiEntryPoint
     */
    private $shoppingApiEntryPoint;
    /**
     * @var NormalizedCategoryRepository
     */
    private $normalizedCategoryRepository;
    /**
     * @var ApplicationShopRepository $applicationShopRepository
     */
    private $applicationShopRepository;
    /**
     * @var ShopsRepresentation $shopsRepresentation
     */
    private $shopsRepresentation;

    public function __construct(
        ShoppingApiEntryPoint $shoppingApiEntryPoint,
        NormalizedCategoryRepository $normalizedCategoryRepository,
        ApplicationShopRepository $applicationShopRepository,
        ShopsRepresentation $shopsRepresentation
    ) {
        $this->shoppingApiEntryPoint = $shoppingApiEntryPoint;
        $this->normalizedCategoryRepository = $normalizedCategoryRepository;
        $this->applicationShopRepository = $applicationShopRepository;
        $this->shopsRepresentation = $shopsRepresentation;

        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:create_native_shop');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->makeEasier($input, $output);

        $ebaySellersGen = Util::createGenerator($this->shopsRepresentation->getRepresentation());

        foreach ($ebaySellersGen as $entry) {
            $item = $entry['item'];

            /** @var UserItem $user */
            $user = $this->getUser(
                $item['name'],
                $item['global_id']
            );

            if ($item['marketplace']->equals(MarketplaceType::fromValue('Ebay'))) {
                if (!GlobalIdInformation::instance()->has($item['global_id'])) {
                    $message = sprintf(
                        'Invalid Ebay %s global id given',
                        $item['global_id']
                    );

                    throw new \RuntimeException($message);
                }
            }

            $this->doesShopExist(
                $user->getStoreName(),
                (string) $item['marketplace']
            );

            $applicationShop = $this->createApplicationShop($item, $user);

            $this->applicationShopRepository->getManager()->persist($applicationShop);
        }

        $this->applicationShopRepository->getManager()->flush();
    }
    /**
     * @param string $name
     * @param string $marketplace
     * @throws \RuntimeException
     */
    private function doesShopExist(
        string $name,
        string $marketplace
    ) {
        $existingMarketplace = $this->applicationShopRepository->findBy([
            'applicationName' => $name,
            'marketplace' => $marketplace
        ]);

        if (!empty($existingMarketplace)) {
            $message = sprintf(
                'Application shop with name %s in marketplace %s already exists',
                $name,
                $marketplace
            );

            throw new \RuntimeException($message);
        }
    }
    /**
     * @param iterable $data
     * @param UserItem $user
     * @return ApplicationShop
     */
    private function createApplicationShop(
        iterable $data,
        UserItem $user
    ): ApplicationShop {
        return new ApplicationShop(
            $data['name'],
            $user->getStoreName(),
            $data['global_id'],
            (string) $data['marketplace'],
            $data['category']
        );
    }
    /**
     * @param string $sellerName
     * @param string|null $globalId
     * @return UserItem
     */
    private function getUser(string $sellerName, string $globalId = null): UserItem
    {
        $userProfileModel = $this->getUserProfileModel($sellerName, $globalId);

        /** @var GetUserProfileResponse $response */
        $response = $this->shoppingApiEntryPoint->getUserProfile($userProfileModel);

        return $response->getUser();
    }
    /**
     * @return iterable
     */
    private function getEbaySellerInfo(): iterable
    {
        return [
            [
                'name' => 'musicmagpie',
                'global_id' => GlobalIdInformation::EBAY_GB,
                'category' => $this->normalizedCategoryRepository->findOneBy([
                    'name' => 'Books, Music & Movies',
                ]),
                'marketplace' => MarketplaceType::fromValue('Ebay'),
            ],
            [
                'name' => 'medimops',
                'global_id' => GlobalIdInformation::EBAY_DE,
                'category' => $this->normalizedCategoryRepository->findOneBy([
                    'name' => 'Books, Music & Movies',
                ]),
                'marketplace' => MarketplaceType::fromValue('Ebay'),
            ],
        ];
    }
    /**
     * @param string $userId
     * @param string|null $globalId
     * @return ShoppingApiModel
     */
    private function getUserProfileModel(
        string $userId,
        string $globalId = null
    ): ShoppingApiModel {
        $queries = TypedArray::create('integer', Query::class);

        if (is_string($globalId)) {
            $globalId = new Query(
                'GLOBAL-ID',
                $globalId
            );

            $queries[] = $globalId;
        }

        $callnameQuery = new Query(
            'callname',
            'GetUserProfile'
        );

        $userIdQuery = new Query(
            'UserId',
            $userId
        );

        $includeSelectorQuery = new Query(
            'IncludeSelector',
            'Details'
        );

        $queries[] = $callnameQuery;
        $queries[] = $userIdQuery;
        $queries[] = $includeSelectorQuery;

        $getUserProfile = new GetUserProfile($queries);

        $shoppingApiModel = new ShoppingApiModel(
            $getUserProfile,
            TypedArray::create('integer', ItemFilter::class)
        );

        return $shoppingApiModel;
    }
}