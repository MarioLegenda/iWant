<?php

namespace App\Symfony\Command;

use App\Doctrine\Repository\ToggleCacheRepository;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use App\Doctrine\Entity\ToggleCache as ToggleCacheEntity;

class ToggleCache extends BaseCommand
{
    /**
     * @var ToggleCacheRepository $toggleCacheRepository
     */
    private $toggleCacheRepository;
    /**
     * ToggleCache constructor.
     * @param ToggleCacheRepository $toggleCacheRepository
     */
    public function __construct(
        ToggleCacheRepository $toggleCacheRepository
    ) {
        parent::__construct();

        $this->toggleCacheRepository = $toggleCacheRepository;
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:toggle_cache');

        $this->addOption('toggle', '-t', InputOption::VALUE_REQUIRED, 'Enable or disable a cache');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->makeEasier($input, $output);

        $answer = $this->getAnswers();
        $resolvedToggle = $this->resolveToggle();
        /** @var ToggleCacheEntity $cacheToggle */
        $cacheToggle = $this->getCacheToggle();

        switch ($answer) {
            case 'all_requests_cache':
                $cacheToggle->setAllRequestCache($resolvedToggle);

                break;
            case 'todays_products_cache':
                $cacheToggle->setTodaysKeywordsCache($resolvedToggle);

                break;
            default:
                $message = sprintf(
                    'Cache type not found. Check the switch code'
                );

                throw new \RuntimeException($message);
        }

        $this->toggleCacheRepository->persistAndFlush($cacheToggle);

        $this->output->writeln(sprintf(
            '<info>Cache toggled successfully. Exiting</info>'
        ));
    }
    /**
     * @return bool
     */
    private function resolveToggle(): bool
    {
        $toggle = $this->input->getOption('toggle');

        if (is_null($toggle)) {
            $message = sprintf(
                'You have to provide a --toggle (-t) option with values either true or false'
            );

            throw new \RuntimeException($message);
        }

        if ($toggle !== 'true' and $toggle !== 'false') {
            $message = sprintf(
                '--toggle (-t) option has to be either true or false'
            );

            throw new \RuntimeException($message);
        }

        return ($toggle) === 'true';
    }
    /**
     * @return ToggleCacheEntity
     */
    private function getCacheToggle(): ToggleCacheEntity
    {
        return $this->toggleCacheRepository->findAll()[0];
    }
    /**
     * @return string
     */
    private function getAnswers(): string
    {
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');

        $cacheTypesQuestion = new ChoiceQuestion(
            'Which cache do you want to toggle',
            [
                'all_requests_cache',
                'todays_products_cache',
            ]
        );

        $cacheType = $questionHelper->ask($this->input, $this->output, $cacheTypesQuestion);

        return $cacheType;
    }
}