<?php

namespace App\Symfony\Command;

use App\Doctrine\Entity\Country;
use App\Doctrine\Repository\CountryRepository;
use App\Library\Http\HttpCommunicator;
use App\Library\Http\Request;
use App\Library\Util\Util;
use App\Symfony\Exception\WrapperHttpException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateCountries extends BaseCommand
{
    /**
     * @var string $endpoint
     */
    private $endpoint;
    /**
     * @var HttpCommunicator $httpCommunicator
     */
    private $httpCommunicator;
    /**
     * @var CountryRepository $countryRepository
     */
    private $countryRepository;
    /**
     * PopulateCountries constructor.
     * @param HttpCommunicator $httpCommunicator
     * @param CountryRepository $countryRepository
     */
    public function __construct(
        HttpCommunicator $httpCommunicator,
        CountryRepository $countryRepository
    ) {
        $this->httpCommunicator = $httpCommunicator;
        $this->countryRepository = $countryRepository;
        $this->endpoint = 'https://restcountries.eu/rest/v2/all';

        parent::__construct();
    }
    /**
     * @inheritdoc
     */
    public function configure()
    {
        $this->setName('app:load_countries');
    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \App\Symfony\Exception\WrapperHttpException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->makeEasier($input, $output);

        $this->output->writeln(sprintf(
            '<info>Starting %s command</info>',
            $this->getName()
        ));

        $request = $this->createRequest();

        $this->output->writeln(sprintf(
            '<info>Sending GET request to endpoint %s</info>',
            $this->endpoint
        ));

        try {
            $response = $this->httpCommunicator->get($request);

            $this->output->writeln(sprintf(
                '<info>Request to endpoint %s has succeeded. Creating a response and persisting to database</info>',
                $this->endpoint
            ));

            $normalizedResponse = $this->normalizeResponse(
                json_decode($response->getResponseString(), true)
            );

            $responseGen = Util::createGenerator($normalizedResponse);

            foreach ($responseGen as $entry) {
                $item = $entry['item'];

                $this->countryRepository->persistAndFlushIfNotExists(
                    $this->createCountryEntity(
                        $item['name'],
                        $item['alpha3Code'],
                        $item['flag'],
                        $item['currencyCode']
                    )
                );
            }

            $this->output->writeln(sprintf(
                '<info>Command has succeeded without problems. Exiting</info>',
                $this->endpoint
            ));
        } catch (WrapperHttpException $e) {
            $this->output->writeln(sprintf(
                '<error>Command failed with exception error %s</error>',
                $e->getMessage()
            ));
        }
    }
    /**
     * @param string $name
     * @param string $alpha3Code
     * @param string $currencyCode|null
     * @param string $flag
     * @return Country
     */
    private function createCountryEntity(
        string $name,
        string $alpha3Code,
        string $flag,
        string $currencyCode = null
    ): Country {
        return new Country(
            $name,
            $alpha3Code,
            $flag,
            $currencyCode
        );
    }
    /**
     * @return Request
     */
    private function createRequest(): Request
    {
        $request = new Request(
            $this->endpoint
        );

        return $request;
    }
    /**
     * @param iterable $response
     * @return iterable
     */
    private function normalizeResponse(iterable $response): iterable
    {
        $countries = [];
        $responseGen = Util::createGenerator($response);

        foreach ($responseGen as $entry) {
            $item = $entry['item'];

            $currencyCode = null;
            if (!empty($item['currencies'])) {
                $currencyCode = $item['currencies'][0]['code'];
            }

            $countries[] = [
                'name' => $item['name'],
                'alpha3Code' => $item['alpha3Code'],
                'currencyCode' => $currencyCode,
                'flag' => $item['flag'],
            ];
        }

        return $countries;
    }
}