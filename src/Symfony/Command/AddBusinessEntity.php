<?php

namespace App\Symfony\Command;

use App\Doctrine\Entity\EbayBusinessEntity;
use App\Doctrine\Repository\EbayBusinessEntityRepository;
use App\Ebay\Business\Finder;
use App\Ebay\Library\Information\GlobalIdInformation;
use App\Ebay\Library\Model\ShoppingApiRequestModelInterface;
use App\Ebay\Library\Response\ResponseModelInterface;
use App\Ebay\Library\Response\ShoppingApi\GetUserProfileResponseInterface;
use App\Ebay\Library\Response\ShoppingApi\Json\User\User;
use App\Ebay\Presentation\Model\ItemFilter;
use App\Ebay\Presentation\Model\Query;
use App\Ebay\Presentation\ShoppingApi\Model\ShoppingApiModel;
use App\Library\Infrastructure\Helper\TypedArray;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Ebay\Presentation\ShoppingApi\Model\GetUserProfile as GetUserProfileModel;

class AddBusinessEntity extends BaseCommand
{
    /**
     * @var Finder $finder
     */
    private $finder;
    /**
     * @var EbayBusinessEntityRepository $ebayBusinessEntityRepository
     */
    private $ebayBusinessEntityRepository;
    /**
     * AddBusinessEntity constructor.
     * @param EbayBusinessEntityRepository $ebayBusinessEntityRepository
     * @param Finder $finder
     */
    public function __construct(
        EbayBusinessEntityRepository $ebayBusinessEntityRepository,
        Finder $finder
    ) {
        $this->ebayBusinessEntityRepository = $ebayBusinessEntityRepository;
        $this->finder = $finder;

        parent::__construct();
    }
    /**
     * @void
     */
    public function configure()
    {
        $this->setName('app:add_ebay_business_entity');

        $this->addArgument('entity_name', InputArgument::REQUIRED, 'Store name');
        $this->addArgument('global_id', InputArgument::REQUIRED, 'Ebay site global id');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->makeEasier($input, $output);

        $this->validate();

        $shoppingApiModel = $this->createFindingApiRequestModel();

        $output->writeln(sprintf(
            '<info>Fetching user %s from eBay</info>',
            $this->input->getArgument('entity_name')
        ));

        /** @var GetUserProfileResponseInterface|ResponseModelInterface $response */
        $response = $this->finder->getUserProfile($shoppingApiModel);

        if (!$response->getRoot()->isSuccess()) {
            $message = sprintf(
                '<error>Request failed with response %s</error>',
                $response->getRawResponse()
            );

            throw new \RuntimeException($message);
        }

        $this->output->writeln('<info>Fetched user information from eBay</info>');

        $ebayBusinessEntity = $this->createEbayBusinessEntity(
            $response->getUser(),
            $this->input->getArgument('global_id')
        );

        $existingBusinessEntity = $this->ebayBusinessEntityRepository->findOneBy([
            'displayName' => $ebayBusinessEntity->getDisplayName(),
        ]);

        if ($existingBusinessEntity instanceof EbayBusinessEntity) {
            $message = sprintf(
                'Business entity with displayName %s already exists',
                $existingBusinessEntity->getDisplayName()
            );

            throw new \RuntimeException($message);
        }

        $this->output->writeln('<info>Saving new user to the database</info>');

        $this->ebayBusinessEntityRepository->persistAndFlush($ebayBusinessEntity);

        $this->output->writeln('<info>User saved. Command successful. The following is the new user information fetched from eBay (not necessarily saved in the database): </info>');
        $this->output->writeln('');

        $userAsArray = $response->getUser()->toArray();

        foreach ($userAsArray as $keyType => $keyValue) {
            $output->writeln(
                sprintf('<info>%s: %s</info>', ucfirst($keyType), $keyValue)
            );
        }
    }
    /**
     * @return ShoppingApiRequestModelInterface
     */
    private function createFindingApiRequestModel(): ShoppingApiRequestModelInterface
    {
        $callnameQuery = new Query(
            'callname',
            'GetUserProfile'
        );

        $userIdQuery = new Query(
            'UserId',
            $this->input->getArgument('entity_name')
        );

        $includeSelectorQuery = new Query(
            'IncludeSelector',
            'Details'
        );

        $queries = TypedArray::create('integer', Query::class);

        $queries[] = $callnameQuery;
        $queries[] = $userIdQuery;
        $queries[] = $includeSelectorQuery;

        $getUserProfile = new GetUserProfileModel($queries);

        $shoppingApiModel = new ShoppingApiModel(
            $getUserProfile,
            TypedArray::create('integer', ItemFilter::class)
        );

        return $shoppingApiModel;
    }
    /**
     * @throws \RuntimeException
     */
    private function validate(): void
    {
        $globalId = $this->input->getArgument('global_id');

        if (!GlobalIdInformation::instance()->has($globalId)) {
            $message = sprintf(
                'Global ID %s not found',
                $globalId
            );

            throw new \RuntimeException($message);
        }
    }
    /**
     * @param User $userItem
     * @param string $globalId
     * @return EbayBusinessEntity
     */
    private function createEbayBusinessEntity(
        User $userItem,
        string $globalId
    ): EbayBusinessEntity {
        return new EbayBusinessEntity(
            $userItem->getUserId(),
            $globalId,
            jsonEncodeWithFix($userItem->toArray()),
            $userItem->getStoreName(),
            $userItem->getSellerBusinessType()
        );
    }
}