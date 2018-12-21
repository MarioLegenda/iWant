<?php

namespace App\Symfony\Command;

use App\Doctrine\Entity\EbayBusinessEntity;
use App\Doctrine\Repository\EbayBusinessEntityRepository;
use App\Ebay\Business\Finder;
use App\Ebay\Library\Model\ShoppingApiRequestModelInterface;
use App\Ebay\Library\Response\ResponseModelInterface;
use App\Ebay\Library\Response\ShoppingApi\GetUserProfileResponseInterface;
use App\Ebay\Library\Response\ShoppingApi\ResponseItem\UserItem;
use App\Ebay\Presentation\Model\ItemFilter;
use App\Ebay\Presentation\Model\Query;
use App\Ebay\Presentation\ShoppingApi\Model\ShoppingApiModel;
use App\Library\Infrastructure\Helper\TypedArray;
use App\Library\Util\Util;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Ebay\Presentation\ShoppingApi\Model\GetUserProfile as GetUserProfileModel;

class BatchAddBusinessEntity extends BaseCommand
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
     * @var array $ebayBusinessEntites
     */
    private $ebayBusinessEntites = [
        [
            'name' => 'musicmagpie',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'worldofbooks08',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'medimops',
            'globalId' => 'EBAY-DE',
        ]
    ];
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
        $this->setName('app:batch_add_ebay_business_entity');
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

        $ebayBusinessEntitiesSeedDataGen = Util::createGenerator($this->ebayBusinessEntites);

        $this->output->writeln('<info>Starting batch adding ebay business entities');

        foreach ($ebayBusinessEntitiesSeedDataGen as $entry) {
            $item = $entry['item'];

            $this->addBusinessEntity(
                $item['name'],
                $item['globalId']
            );
        }

        $this->output->writeln(sprintf(
            '<info>Finished processing. Command successful</info>'
        ));
    }
    /**
     * @param string $entityName
     * @param string $globalId
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function addBusinessEntity(
        string $entityName,
        string $globalId
    ): void {
        $shoppingApiModel = $this->createFindingApiRequestModel($entityName);

        /** @var GetUserProfileResponseInterface|ResponseModelInterface $response */
        $response = $this->finder->getUserProfile($shoppingApiModel);

        if ($response->isErrorResponse()) {
            $message = sprintf(
                '<error>Request failed with response %s</error>',
                $response->getRawResponse()
            );

            throw new \RuntimeException($message);
        }

        $ebayBusinessEntity = $this->createEbayBusinessEntity(
            $response->getUser(),
            $globalId
        );

        $existingBusinessEntity = $this->ebayBusinessEntityRepository->findOneBy([
            'displayName' => $ebayBusinessEntity->getDisplayName(),
        ]);

        if ($existingBusinessEntity instanceof EbayBusinessEntity) {
            $existingBusinessEntity->setName($ebayBusinessEntity->getName());
            $existingBusinessEntity->setGlobalId($ebayBusinessEntity->getGlobalId());
            $existingBusinessEntity->setType($ebayBusinessEntity->getType());

            $this->ebayBusinessEntityRepository->persistAndFlush($existingBusinessEntity);

            $this->output->writeln(sprintf(
                '<comment>Updated %s business entity</comment>', $entityName
            ));

            return;
        }

        $this->ebayBusinessEntityRepository->persistAndFlush($ebayBusinessEntity);

        $this->output->writeln(sprintf(
            '<info>Added %s</info>',
            $entityName
        ));
    }
    /**
     * @param string $entityName
     * @return ShoppingApiRequestModelInterface
     */
    private function createFindingApiRequestModel(string $entityName): ShoppingApiRequestModelInterface
    {
        $callnameQuery = new Query(
            'callname',
            'GetUserProfile'
        );

        $userIdQuery = new Query(
            'UserId',
            $entityName
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
     * @param UserItem $userItem
     * @param string $globalId
     * @return EbayBusinessEntity
     */
    private function createEbayBusinessEntity(
        UserItem $userItem,
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