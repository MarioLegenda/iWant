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
        ],
        [
            'name' => 'universalgadgets01',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'babztech',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'Thinkprice',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'lmelectrical',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'angling_warehouse',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'aceparts_uk',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'cheapest_electrical',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'hezhl2011',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'stella-comm',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'madaboutcostumes',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'bamford_trading',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'lcd-wall-brackets',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'ghostbikes_uk',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'fancy_dress_discount_store',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'cramptonandmoore',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'paulsanglingsupplies',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'qfished',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'stomp-group',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'bedroom_furniture_direct',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'belle-lingerie',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'prestige.fitness.direct',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'ukawesomestuff928',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'home-un-leisure',
            'globalId' => 'EBAY-GB',
        ],
        [
            'name' => 'grt104',
            'globalId' => 'EBAY-GB',
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
        $this->output->writeln('');

        foreach ($ebayBusinessEntitiesSeedDataGen as $entry) {
            $item = $entry['item'];

            $this->addBusinessEntity(
                $item['name'],
                $item['globalId']
            );
        }

        $this->output->writeln('');
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

        if ($response->getUser()->getStatus() !== 'Confirmed') {
            $this->output->writeln(sprintf(
                '<error>The status of \'%s\' seller is not \'Confirmed\' but %s. Skipping</error>',
                $response->getUser()->getUserId(),
                $response->getUser()->getStatus()
            ));

            return;
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
                '<comment>Updated \'%s\' business entity. Memory usage: %s</comment>',
                $entityName,
                $this->getMBUsage()
            ));

            return;
        }

        $this->ebayBusinessEntityRepository->persistAndFlush($ebayBusinessEntity);

        $this->output->writeln(sprintf(
            '<info>Added \'%s\' business entity. Memory usage: %s</info>',
            $entityName,
            $this->getMBUsage()
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
    /**
     * @return string
     */
    private function getMBUsage(): string
    {
        return sprintf('%.2f MB', memory_get_usage() / 1000 / 1000);
    }
}