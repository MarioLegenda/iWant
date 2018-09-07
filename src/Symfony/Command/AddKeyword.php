<?php

namespace App\Symfony\Command;

use App\Doctrine\Entity\NativeTaxonomy;
use App\Doctrine\Entity\TodayKeyword;
use App\Doctrine\Repository\NativeTaxonomyRepository;
use App\Doctrine\Repository\TodaysKeywordRepository;
use App\Library\MarketplaceType;
use App\Library\Representation\MarketplaceRepresentation;
use App\Library\Util\Util;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class AddKeyword extends BaseCommand
{
    /**
     * @var MarketplaceRepresentation $marketplaceRepresentation
     */
    private $marketplaceRepresentation;
    /**
     * @var NativeTaxonomyRepository $normalizedCategoryRepository
     */
    private $normalizedCategoryRepository;
    /**
     * @var TodaysKeywordRepository $todaysKeywordRepository
     */
    private $todaysKeywordRepository;
    /**
     * AddKeyword constructor.
     * @param MarketplaceRepresentation $marketplaceRepresentation
     * @param NativeTaxonomyRepository $normalizedCategoryRepository
     * @param TodaysKeywordRepository $todaysKeywordRepository
     */
    public function __construct(
        MarketplaceRepresentation $marketplaceRepresentation,
        NativeTaxonomyRepository $normalizedCategoryRepository,
        TodaysKeywordRepository $todaysKeywordRepository
    ) {
        $this->marketplaceRepresentation = $marketplaceRepresentation;
        $this->normalizedCategoryRepository = $normalizedCategoryRepository;
        $this->todaysKeywordRepository = $todaysKeywordRepository;

        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:add_keyword');
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
            '<info>Started %s command</info>',
            $this->getName()
        ));

        $answers = $this->getAnswers();

        /** @var TodayKeyword $todayKeyword */
        $todayKeyword = $this->createTodaysKeywordFromAnswers($answers);

        $this->output->writeln('');
        $this->output->writeln(sprintf(
            '<info>Saving today keyword to database</info>'
        ));

        $this->todaysKeywordRepository->persistAndFlush($todayKeyword);

        $this->output->writeln(sprintf(
            '<info>Command successfully executed. Exiting</info>'
        ));
    }
    /**
     * @param array $answers
     * @return TodayKeyword
     */
    private function createTodaysKeywordFromAnswers(array $answers): TodayKeyword
    {
        $keyword = $answers['keyword'];
        $marketplace = $answers['marketplace'];
        $normalizedCategory = $answers['category'];

        return new TodayKeyword($keyword, $marketplace, $normalizedCategory);
    }
    /**
     * @return array
     */
    private function getAnswers(): array
    {
        /** @var NativeTaxonomy[] $allNormalizedCategories */
        $allNormalizedCategories = $this->normalizedCategoryRepository->findAll();
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        $resolvedMarketplaces = $this->resolveMarketplaces();
        $normalizedCategories = $this->resolveNormalizedCategories($allNormalizedCategories);

        $keywordQuestion = new Question('Keyword: ');
        $marketplaceQuestion = new ChoiceQuestion(
            'Select a marketplace: ',
            $resolvedMarketplaces
        );
        $normalizedCategoryQuestion = new ChoiceQuestion(
            'Select a normalized category',
            $normalizedCategories
        );

        $keyword = $questionHelper->ask($this->input, $this->output, $keywordQuestion);
        $marketplace = $questionHelper->ask($this->input, $this->output, $marketplaceQuestion);
        $normalizedCategory = $questionHelper->ask($this->input, $this->output, $normalizedCategoryQuestion);

        return [
            'keyword' => $keyword,
            'marketplace' => $resolvedMarketplaces[$marketplace],
            'category' => $this->resolveCategoryToObject(
                $normalizedCategories[$normalizedCategory],
                $allNormalizedCategories
            )
        ];
    }
    /**
     * @param string $name
     * @param NativeTaxonomy[] $normalizedCategories
     * @return NativeTaxonomy
     */
    private function resolveCategoryToObject(
        string $name,
        array $normalizedCategories
    ): NativeTaxonomy {
        $normalizedCategoriesGen = Util::createGenerator($normalizedCategories);

        foreach ($normalizedCategoriesGen as $entry) {
            /** @var NativeTaxonomy $category */
            $category = $entry['item'];

            if ($category->getName() === $name) {
                return $category;
            }
        }

        $message = sprintf(
            'An error occurred. Category with name \'%s\' not found',
            $name
        );

        throw new \RuntimeException($message);
    }
    /**
     * @return array
     */
    private function resolveMarketplaces(): array
    {
        $marketplaces = $this->marketplaceRepresentation->toArray();
        $resolved = [];

        /** @var MarketplaceType $marketplace */
        foreach ($marketplaces as $marketplace) {
            $resolved[(string) $marketplace] = (string) $marketplace;
        }

        return $resolved;
    }
    /**
     * @param NativeTaxonomy[] $normalizedCategories
     * @return array
     */
    private function resolveNormalizedCategories(array $normalizedCategories): array
    {
        $categoryArray = [];
        /** @var NativeTaxonomy $normalizedCategory */
        foreach ($normalizedCategories as $normalizedCategory) {
            $categoryArray[$normalizedCategory->getName()] = $normalizedCategory->getName();
        }

        return $categoryArray;
    }
}