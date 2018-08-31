<?php

namespace App\Symfony\Command;


use App\Bonanza\Presentation\Model\ItemFilter;
use App\Ebay\Business\Finder;
use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;
use App\Ebay\Presentation\FindingApi\Model\FindItemsInEbayStores;
use App\Ebay\Presentation\FindingApi\Model\Query;
use App\Library\Infrastructure\Helper\TypedArray;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadProductsFromEbayStore extends BaseCommand
{
    /**
     * @var Finder $finder
     */
    private $finder;
    /**
     * LoadProductsFromEbayStore constructor.
     * @param Finder $finder
     */
    public function __construct(
        Finder $finder
    ) {
        $this->finder = $finder;

        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:load_products_from_ebay_store');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->makeEasier($input, $output);

        $findingApiModel = $this->createFindingApiRequestModel();

        /** @var FindingApiResponseModelInterface $response */
        $response = $this->finder->findItemsInEbayStores($findingApiModel);
    }
    /**
     * @return FindingApiRequestModelInterface
     */
    private function createFindingApiRequestModel(): FindingApiRequestModelInterface
    {
        $query = new Query(
            'storeName',
            'musicMagpie Shop'
        );

        $paginationInputQuery = new Query(
            'paginationInput.entriesPerPage',
            100
        );

        $keywordsQuery = new Query(
            'keywords',
            'Gaga'
        );

        $queries = TypedArray::create('integer', Query::class);
        $queries[] = $query;
        $queries[] = $paginationInputQuery;
        $queries[] = $keywordsQuery;

        $findItemsInEbayStore = new FindItemsInEbayStores($queries);

        $findingApiModel = new FindingApiModel(
            $findItemsInEbayStore,
            TypedArray::create('integer', ItemFilter::class)
        );

        return $findingApiModel;
    }
}