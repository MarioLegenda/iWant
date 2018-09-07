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
use App\Library\Representation\MarketplaceRepresentation;
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
    /**
     * CreateNativeShops constructor.
     * @param ShoppingApiEntryPoint $shoppingApiEntryPoint
     * @param NormalizedCategoryRepository $normalizedCategoryRepository
     * @param ApplicationShopRepository $applicationShopRepository
     * @param ShopsRepresentation $shopsRepresentation
     */
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
        $this->setName('app:create_native_shops');
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

        $this->output->writeln(sprintf(
            '<info>Starting command %s</info>',
            $this->getName()
        ));
        $this->output->writeln('');

        $sellersGen = Util::createGenerator($this->shopsRepresentation->getRepresentation());

        foreach ($sellersGen as $entry) {
            $item = $entry['item'];

            $data = $this->resolveUserData($item);

            $exists = $this->doesShopExist(
                $data['storeName'],
                (string) $data['data']['marketplace']
            );

            if ($exists) {
                $this->output->writeln(sprintf(
                    '<comment>Store \'%s\' of marketplace %s already exists. Continuing</comment>',
                    $data['storeName'],
                    (string) $data['data']['marketplace']
                ));

                continue;
            }

            $applicationShop = $this->createApplicationShop(
                $data['data'],
                $data['storeName']
            );

            $this->applicationShopRepository->getManager()->persist($applicationShop);

            $this->output->writeln(sprintf(
                '<info>Persisted store \'%s\' from the %s marketplace</info>',
                $data['storeName'],
                (string) $data['data']['marketplace']
            ));
        }

        $this->applicationShopRepository->getManager()->flush();

        $this->output->writeln('');
        $this->output->writeln(sprintf(
            '<info>Application shops created successfully. Existing</info>'
        ));
    }
    /**
     * @param iterable $item
     * @return iterable
     */
    private function resolveUserData(
        iterable $item
    ): iterable {
        $marketplace = $item['marketplace'];

        if ($marketplace->equals(MarketplaceType::fromValue('Ebay'))) {
            if (!GlobalIdInformation::instance()->has($item['global_id'])) {
                $message = sprintf(
                    'Invalid Ebay %s global id given',
                    $item['global_id']
                );

                throw new \RuntimeException($message);
            }
        }

        if ($marketplace->equals(MarketplaceType::fromValue('Ebay'))) {
            /** @var UserItem $user */
            $user = $this->getEbayUser(
                $item['name'],
                $item['global_id']
            );

            return [
                'storeName' => $user->getStoreName(),
                'data' => $item,
            ];
        }

        if ($marketplace->equals(MarketplaceType::fromValue('Etsy'))) {
            return [
                'storeName' => $item['name'],
                'data' => $item,
            ];
        }
    }
    /**
     * @param string $name
     * @param string $marketplace
     * @return null
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
            return true;
        }
    }
    /**
     * @param iterable $data
     * @param string $storeName
     * @return ApplicationShop
     */
    private function createApplicationShop(
        iterable $data,
        string $storeName
    ): ApplicationShop {
        return new ApplicationShop(
            $data['name'],
            $storeName,
            (string) $data['marketplace'],
            $data['category'],
            $data['global_id']
        );
    }
    /**
     * @param string $sellerName
     * @param string|null $globalId
     * @return UserItem
     */
    private function getEbayUser(string $sellerName, string $globalId = null): UserItem
    {
        $userProfileModel = $this->getUserProfileModel($sellerName, $globalId);

        /** @var GetUserProfileResponse $response */
        $response = $this->shoppingApiEntryPoint->getUserProfile($userProfileModel);

        return $response->getUser();
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