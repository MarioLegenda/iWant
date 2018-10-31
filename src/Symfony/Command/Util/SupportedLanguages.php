<?php

namespace App\Symfony\Command\Util;

use App\Symfony\Command\BaseCommand;
use App\Yandex\Library\Request\RequestFactory;
use App\Yandex\Library\Response\SupportedLanguagesResponse;
use App\Yandex\Presentation\EntryPoint\YandexEntryPoint;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SupportedLanguages extends BaseCommand
{
    /**
     * @var YandexEntryPoint $yandexEntryPoint
     */
    private $yandexEntryPoint;
    /**
     * SupportedLanguages constructor.
     * @param YandexEntryPoint $yandexEntryPoint
     */
    public function __construct(
        YandexEntryPoint $yandexEntryPoint
    ) {
        $this->yandexEntryPoint = $yandexEntryPoint;

        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:supported_yandex_laguages_list');

        $this->addOption('locale', '-l', InputOption::VALUE_REQUIRED, 'Choose a locale');
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
            '<info>Starting command %s</info>',
            $this->getName()
        ));

        $locale = $this->input->getOption('locale');

        if (is_null($locale)) {
            $locale = 'en';
        }

        $getLangsRequestModel = RequestFactory::createSupportedLanguagesListingRequestModel($locale);

        /** @var SupportedLanguagesResponse $response */
        $response = $this->yandexEntryPoint->getSupportedLanguages($getLangsRequestModel);

        $languages = $response->getAllLanguages();

        $output->writeln(sprintf(
            'Language list:'
        ));
        $output->writeln('');

        foreach ($languages as $code => $language) {
            $output->writeln(sprintf(
                '<info>Code: %s, Language: %s</info>',
                $code,
                $language
            ));
        }

        $output->writeln('');
        $output->writeln(sprintf(
            '<info>Command %s finished. Exiting</info>',
            $this->getName()
        ));
    }
}