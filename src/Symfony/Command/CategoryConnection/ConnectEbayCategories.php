<?php

namespace App\Symfony\Command\CategoryConnection;

use App\Doctrine\Entity\EbayRootCategory;
use App\Doctrine\Entity\NativeTaxonomy;
use App\Doctrine\Repository\ApplicationShopRepository;
use App\Doctrine\Repository\EbayRootCategoryRepository;
use App\Doctrine\Repository\NativeTaxonomyRepository;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Ebay\Library\ItemFilter\ItemFilter;
use App\Ebay\Library\Model\ShoppingApiRequestModelInterface;
use App\Ebay\Library\Response\ShoppingApi\GetCategoryInfoResponseInterface;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\Category;
use App\Ebay\Presentation\Model\Query;
use App\Ebay\Presentation\ShoppingApi\EntryPoint\ShoppingApiEntryPoint;
use App\Ebay\Presentation\ShoppingApi\Model\GetCategoryInfo;
use App\Ebay\Presentation\ShoppingApi\Model\ShoppingApiModel;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\MarketplaceType;
use App\Library\Util\Util;
use App\Symfony\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConnectEbayCategories extends BaseCommand
{
    /**
     * @var NativeTaxonomyRepository $nativeTaxonomyRepository
     */
    private $nativeTaxonomyRepository;
    /**
     * @var ShoppingApiEntryPoint $shoppingApiEntryPoint
     */
    private $shoppingApiEntryPoint;
    /**
     * @var EbayRootCategoryRepository $ebayRootCategoryRepository
     */
    private $ebayRootCategoryRepository;
    /**
     * @var ApplicationShopRepository $applicationShopRepository
     */
    private $applicationShopRepository;
    /**
     * ConnectEbayCategories constructor.
     * @param NativeTaxonomyRepository $nativeTaxonomyRepository
     * @param ApplicationShopRepository $applicationShopRepository
     * @param EbayRootCategoryRepository $ebayRootCategoryRepository
     * @param ShoppingApiEntryPoint $shoppingApiEntryPoint
     */
    public function __construct(
        NativeTaxonomyRepository $nativeTaxonomyRepository,
        ApplicationShopRepository $applicationShopRepository,
        EbayRootCategoryRepository $ebayRootCategoryRepository,
        ShoppingApiEntryPoint $shoppingApiEntryPoint
    ) {
        $this->nativeTaxonomyRepository = $nativeTaxonomyRepository;
        $this->shoppingApiEntryPoint = $shoppingApiEntryPoint;
        $this->ebayRootCategoryRepository = $ebayRootCategoryRepository;
        $this->applicationShopRepository = $applicationShopRepository;

        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:connect_ebay_categories');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \App\Symfony\Exception\HttpException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->makeEasier($input, $output);

        $normalizedCategories = $this->nativeTaxonomyRepository->findAll();

        if (empty($normalizedCategories)) {
            $message = sprintf(
                'Normalized categories are empty. Please, use \'app:upsert_native_taxonomy\' command and create all the necessary categories'
            );

            throw new \RuntimeException($message);
        }

        $this->output->writeln(sprintf(
                '<info>Starting command %s</info>',
                $this->getName()
        ));

        $globalIds = $this->getGlobalIds();

        $this->output->writeln(sprintf(
                '<info>Starting writing ebay categories for global ids %s</info>',
                implode(', ', $globalIds)
        ));

        foreach ($globalIds as $globalId) {
            $this->output->writeln('');

            $model = $this->createShoppingApiModel($globalId);


            /** @var GetCategoryInfoResponseInterface $response */
            $response = $this->shoppingApiEntryPoint->getCategoryInfo($model);

            $normalizedCategoryInfo = $this->getNormalizedCategoryInfo();

            /** @var NativeTaxonomy $normalizedCategory */
            foreach ($normalizedCategories as $normalizedCategory) {
                $normalizedCategoryName = $normalizedCategory->getName();

                $ebayCategoryIds = $normalizedCategoryInfo[$normalizedCategoryName];

                $foundEbayRootCategories = $this->findCategoriesInResponse(
                    $response,
                    $ebayCategoryIds
                );

                $this->upsertEbayRootCategory(
                    $normalizedCategory,
                    $foundEbayRootCategories,
                    $globalId
                );
            }
        }

        $this->output->writeln('');
        $this->output->writeln(sprintf(
            '<info>Command finished successfully. Exiting</info>'
        ));
    }
    /**
     * @param NativeTaxonomy $normalizedCategory
     * @param iterable $ebayRootCategories
     * @param string $globalId
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function upsertEbayRootCategory(
        NativeTaxonomy $normalizedCategory,
        iterable $ebayRootCategories,
        string $globalId
    ) {
        /** @var Category $ebayRootCategory */
        foreach ($ebayRootCategories as $ebayRootCategory) {
            $existingEbayRootCategory = $this->ebayRootCategoryRepository->findOneBy([
                'globalId' => $globalId,
                'nativeTaxonomy' => $normalizedCategory,
                'categoryId' => $ebayRootCategory['categoryId']
            ]);

            if ($existingEbayRootCategory instanceof EbayRootCategory) {
                $message = sprintf(
                    'Ebay root category with normalized name %s and connected ebay root category with name %s already exist and are connected. There can be only one connection between a normalized category and a single ebay root category',
                    $normalizedCategory->getName(),
                    $ebayRootCategory['categoryName']
                );

                throw new \RuntimeException($message);
            }

            $ebayCategory = new EbayRootCategory(
                $globalId,
                $ebayRootCategory['categoryId'],
                $ebayRootCategory['categoryIdPath'],
                $ebayRootCategory['categoryLevel'],
                $ebayRootCategory['categoryName'],
                $ebayRootCategory['categoryNamePath'],
                $ebayRootCategory['categoryParentId'],
                $ebayRootCategory['leafCategory'],
                $normalizedCategory
            );

            $this->output->writeln(sprintf(
                '<info>Persisted category ebay root category %s in connection with normalized category %s for global id %s</info>',
                $ebayCategory->getCategoryName(),
                $normalizedCategory->getName(),
                $globalId
            ));

            $this->ebayRootCategoryRepository->getManager()->persist($ebayCategory);
        }

        $this->ebayRootCategoryRepository->getManager()->flush();

        $this->output->writeln(sprintf(
            '<info>All categories for global id %s created successfully</info>',
            $globalId
        ));
    }
    /**
     * @param GetCategoryInfoResponseInterface $response
     * @param iterable $ebayCategoryIds
     * @return iterable
     */
    private function findCategoriesInResponse(
        GetCategoryInfoResponseInterface $response,
        iterable $ebayCategoryIds
    ) {
        $foundCategories = TypedArray::create('integer', Category::class);
        $ebayCategories = $response->getCategories();

        array_filter($ebayCategoryIds, function($categoryId) use ($ebayCategories, $foundCategories) {
            $categoriesGen = Util::createGenerator($ebayCategories);

            foreach ($categoriesGen as $entry) {
                /** @var Category $item */
                $item = $entry['item'];

                if ($item->getCategoryName() === $categoryId) {
                    $foundCategories[] = $item;
                }
            }
        });

        return $foundCategories->toArray();
    }
    /**
     * @param string $globalId
     * @return ShoppingApiRequestModelInterface
     */
    private function createShoppingApiModel(string $globalId): ShoppingApiRequestModelInterface
    {
        $callname = new Query(
            'callname',
            'GetCategoryInfo'
        );

        $categoryId = new Query(
            'CategoryId',
            -1
        );

        $globalId = new Query(
            'GLOBAL-ID',
            $globalId
        );

        $includeSelector = new Query(
            'IncludeSelector',
            'ChildCategories'
        );

        $queries = TypedArray::create('integer', Query::class);

        $queries[] = $categoryId;
        $queries[] = $globalId;
        $queries[] = $callname;
        $queries[] = $includeSelector;

        $callType = new GetCategoryInfo($queries);

        $itemFilters = TypedArray::create('integer', ItemFilter::class);

        return new ShoppingApiModel($callType, $itemFilters);
    }
    /**
     * @return iterable
     */
    private function getNormalizedCategoryInfo(): iterable
    {
        return [
            'Books, Music & Movies' => [
                'Books, Comics & Magazines',
                'DVDs, Films & TV',
                'Music',
            ],
            'Autoparts & Mechanics' => [
                'Cars, Motorcycles & Vehicles',
                'Vehicle Parts & Accessories',
            ],
            'Home & Garden' => [
                'Garden & Patio',
                'Home, Furniture & DIY',
                'Antiques',
            ],
            'Computers, Mobile & Games' => [
                'Computers/Tablets & Networking',
                'DVDs, Films & TV',
                'Mobile Phones & Communication',
            ],
            'Sport' => [
                'Sporting Goods',
                'Sports Memorabilia',
                'Collectables',
            ],
            'Antiques, Art & Collectibles' => [
                'Antiques',
                'Collectables',
                'Art',
            ],
            'Crafts & Handmade' => [
                'Crafts',
                'Jewellery & Watches',
            ],
            'Fashion' => [
                'Clothes, Shoes & Accessories'
            ],
            'Health & Beauty' => [
                'Baby',
                'Health & Beauty',
            ]
        ];
    }
    /**
     * @return array
     */
    private function getGlobalIds(): array
    {
        $globalIds = $this->applicationShopRepository->findGlobalsIdsByMarketplace(MarketplaceType::fromValue('Ebay'));

        return array_unique($globalIds);
    }
}