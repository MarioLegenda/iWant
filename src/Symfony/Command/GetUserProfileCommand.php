<?php

namespace App\Symfony\Command;

use App\Ebay\Business\Finder;
use App\Ebay\Library\ItemFilter\ItemFilter;
use App\Ebay\Library\Model\FindingApiRequestModelInterface;
use App\Ebay\Library\Response\ResponseModelInterface;
use App\Ebay\Library\Response\ShoppingApi\GetUserProfileResponseInterface;
use App\Ebay\Presentation\FindingApi\Model\FindingApiModel;
use App\Ebay\Presentation\ShoppingApi\Model\GetUserProfile;
use App\Ebay\Presentation\Model\Query;
use App\Library\Infrastructure\Helper\TypedArray;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetUserProfileCommand extends BaseCommand
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
        $this->setName('app:get_user_profile');
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

        /** @var GetUserProfileResponseInterface|ResponseModelInterface $response */
        $response = $this->finder->getUserProfile($findingApiModel);

        dump($response->getUser()->getStoreName());
        die();
    }
    /**
     * @return FindingApiRequestModelInterface
     */
    private function createFindingApiRequestModel(): FindingApiRequestModelInterface
    {
        $callnameQuery = new Query(
            'callname',
            'GetUserProfile'
        );

        $userIdQuery = new Query(
            'UserId',
            'musicmagpie'
        );

        $includeSelectorQuery = new Query(
            'IncludeSelector',
            'Details'
        );

        $queries = TypedArray::create('integer', Query::class);

        $queries[] = $callnameQuery;
        $queries[] = $userIdQuery;
        $queries[] = $includeSelectorQuery;

        $getUserProfile = new GetUserProfile($queries);

        $findingApiModel = new FindingApiModel(
            $getUserProfile,
            TypedArray::create('integer', ItemFilter::class)
        );

        return $findingApiModel;
    }
}