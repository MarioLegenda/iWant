<?php

namespace App\Symfony\Command\CategoryConnection;

use App\Doctrine\Repository\NormalizedCategoryRepository;
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
use App\Library\Util\Util;
use App\Symfony\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConnectEbayCategories extends BaseCommand
{
    /**
     * @var NormalizedCategoryRepository $normalizedCategoryRepository
     */
    private $normalizedCategoryRepository;
    /**
     * @var ShoppingApiEntryPoint $shoppingApiEntryPoint
     */
    private $shoppingApiEntryPoint;

    public function __construct(
        NormalizedCategoryRepository $normalizedCategoryRepository,
        ShoppingApiEntryPoint $shoppingApiEntryPoint
    ) {
        $this->normalizedCategoryRepository = $normalizedCategoryRepository;
        $this->shoppingApiEntryPoint = $shoppingApiEntryPoint;

        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:connect_ebay_categories');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $normalizedCategories = $this->normalizedCategoryRepository->findAll();

        $model = $this->createShoppingApiModel();

        /** @var GetCategoryInfoResponseInterface $response */
        $response = $this->shoppingApiEntryPoint->getCategoryInfo($model);

        $categoriesGen = Util::createGenerator($response->getCategories()->toArray());

        /** @var Category $category */
        foreach ($categoriesGen as $entry) {
            $item = $entry['item'];

            
        }
    }
    /**
     * @return ShoppingApiRequestModelInterface
     */
    private function createShoppingApiModel(): ShoppingApiRequestModelInterface
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
            GlobalIdInformation::EBAY_GB
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
}