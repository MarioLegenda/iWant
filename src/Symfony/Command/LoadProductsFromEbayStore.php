<?php

namespace App\Symfony\Command;


use App\Bonanza\Presentation\Model\ItemFilter;
use App\Ebay\Business\Finder;
use App\Ebay\Library\ItemFilter\SellerBusinessType;
use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Library\Response\FindingApi\FindingApiResponseModelInterface;
use App\Ebay\Library\Response\FindingApi\ResponseItem\Child\Item\Item;
use App\Ebay\Library\Response\FindingApi\ResponseItem\PaginationOutput;
use App\Ebay\Library\Response\FindingApi\ResponseItem\SearchResultsContainer;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;
use App\Ebay\Presentation\FindingApi\Model\FindItemsInEbayStores;
use App\Ebay\Presentation\Model\ItemFilterMetadata;
use App\Ebay\Presentation\Model\Query;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\Util;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Ebay\Presentation\Model\ItemFilter as PresentationItemFilter;

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

        $currentPage = 1;
        $activeListings = 0;

        for (;;) {
            /** @var FindingApiResponseModelInterface $response */
            $response = $this->getByPage($currentPage);

            $searchResultsGen = Util::createGenerator($response->getSearchResults());

            foreach ($searchResultsGen as $item) {
                /** @var Item $entry */
                $entry = $item['item'];

                if ($entry->getSellingStatus()->getSellingState() === 'Active') {
                    $activeListings++;
                }
            }

            /** @var PaginationOutput $paginationOutput */
            $paginationOutput = $response->getPaginationOutput();

            if ($currentPage >= $paginationOutput->getTotalPages()) {
                break;
            }

            ++$currentPage;

            $this->output->writeln(sprintf('<info>Number of active items %d</info>', $activeListings));
        }
    }
    /**
     * @param int $pageNumber
     * @return FindingApiResponseModelInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function getByPage(int $pageNumber): FindingApiResponseModelInterface
    {
        $findingApiModel = $this->createFindingApiRequestModel($pageNumber);

        /** @var FindingApiResponseModelInterface $response */
        $response = $this->finder->findItemsInEbayStores($findingApiModel);

        return $response;
    }
    /**
     * @param int $pageNumber
     * @return FindingApiRequestModelInterface
     */
    private function createFindingApiRequestModel(int $pageNumber): FindingApiRequestModelInterface
    {
        $query = new Query(
            'storeName',
            'musicMagpie Shop'
        );

        $paginationInputQuery = new Query(
            'paginationInput.entriesPerPage',
            100
        );

        $pageNumber = new Query(
            'paginationInput.pageNumber',
            $pageNumber
        );

        $queries = TypedArray::create('integer', Query::class);
        $queries[] = $query;
        $queries[] = $paginationInputQuery;
        $queries[] = $pageNumber;

        $findItemsInEbayStore = new FindItemsInEbayStores($queries);

        $itemFilters = TypedArray::create('integer', PresentationItemFilter::class);

        $featuredOnlyItemFilter = new PresentationItemFilter(new ItemFilterMetadata(
            'name',
            'value',
            'HideDuplicateItems',
            [true]
        ));

        $itemFilters[] = $featuredOnlyItemFilter;

        $findingApiModel = new FindingApiModel(
            $findItemsInEbayStore,
            $itemFilters
        );

        return $findingApiModel;
    }
}