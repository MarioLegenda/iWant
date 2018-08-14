<?php

namespace App\Library\OfflineMode;

use App\Ebay\Source\GenericHttpCommunicator;
use App\Library\Http\GenericHttpCommunicatorInterface;
use App\Library\Http\Request;
use App\Library\OfflineMode\OfflineModeMetadata;
use App\Library\Response;

class OfflineMode
{
    /**
     * @var OfflineMode $instance
     */
    private static $instance;
    /**
     * @var string $offlineModeDir
     */
    private $offlineModeDir;
    /**
     * @var string $url
     */
    private $url;
    /**
     * @var resource $requestHandle
     */
    private $requestHandle;
    /**
     * @return OfflineMode
     */
    public static function inst()
    {
        static::$instance = (!static::$instance instanceof static) ? new OfflineMode() : static::$instance;

        return static::$instance;
    }
    /**
     * EbayOfflineMode constructor.
     */
    private function __construct()
    {
        $this->offlineModeDir = OfflineModeMetadata::getOfflineModeDirectory();

        if (!file_exists($this->offlineModeDir.'/responses')) {
            mkdir($this->offlineModeDir.'/responses');
        }
    }
    /**
     * @param GenericHttpCommunicatorInterface $communicator
     * @param Request $request
     * @return string
     */
    public function getResponse(
        GenericHttpCommunicatorInterface $communicator,
        Request $request
    ): string {
        $this->url = $request->getBaseUrl();
        $this->requestHandle = fopen($this->offlineModeDir.'/requests.csv', 'a+');

        if (!$this->isResponseStored($this->url)) {
            $requests = file($this->offlineModeDir.'/requests.csv');

            // if requests.csv is empty, fill it with first request
            if (empty($requests)) {
                // add a request to requests.csv
                fputcsv($this->requestHandle, array(1, $this->url), ';');
                $responseFile = $this->offlineModeDir.'/responses/1.txt';
                fclose(fopen($this->offlineModeDir.'/responses/1.txt', 'a+'));

                /** @var Response $response */
                // makes a request and adds the response to newly created response file
                $response = $communicator->get($request);
                file_put_contents($responseFile, $response->getResponseString());

                $this->closeRequestHandle();

                return $response->getResponseString();
            }

            $lastRequest = preg_split('#;#', array_pop($requests));

            $nextResponse = (int) ++$lastRequest[0];

            fputcsv($this->requestHandle, array($nextResponse, $this->url), ';');

            $responseFile = $this->offlineModeDir.'/responses/'.$nextResponse.'.txt';
            fclose(fopen($responseFile, 'a+'));

            /** @var Response $response */
            $response = $communicator->get($request);
            file_put_contents($responseFile, $response->getResponseString());

            $this->closeRequestHandle();

            return $response->getResponseString();
        }

        if ($this->isResponseStored($this->url) === true) {
            $requests = file($this->offlineModeDir.'/requests.csv');

            foreach ($requests as $line) {
                $requestLine = preg_split('#;#', $line);

                if (trim($requestLine[1]) === $this->url) {
                    $responseFile = $this->offlineModeDir.'/responses/'.$requestLine[0].'.txt';

                    $stringResponse = file_get_contents($responseFile);

                    $this->closeRequestHandle();

                    return $stringResponse;
                }
            }
        }

        $message = sprintf(
            'There is a possible bug in EbayOfflineMode. Please, fix it'
        );

        throw new \RuntimeException($message);
    }

    public function getPostResponse(
        Request $request,
        string $uniqueSaveValue,
        GenericHttpCommunicatorInterface $communicator
    ) {
        $this->url = $request->getBaseUrl();
        $this->requestHandle = fopen($this->offlineModeDir.'/requests.csv', 'a+');

        if (!$this->isResponseStored($uniqueSaveValue)) {
            $requests = file($this->offlineModeDir.'/requests.csv');

            // if requests.csv is empty, fill it with first request
            if (empty($requests)) {
                // add a request to requests.csv
                fputcsv($this->requestHandle, array(1, $uniqueSaveValue), ';');
                $responseFile = $this->offlineModeDir.'/responses/1.txt';
                fclose(fopen($this->offlineModeDir.'/responses/1.txt', 'a+'));

                /** @var Response $response */
                // makes a request and adds the response to newly created response file
                $response = $communicator->post($request);
                file_put_contents($responseFile, $response->getResponseString());

                $this->closeRequestHandle();

                return $response->getResponseString();
            }

            $lastRequest = preg_split('#;#', array_pop($requests));

            $nextResponse = (int) ++$lastRequest[0];

            fputcsv($this->requestHandle, array($nextResponse, $uniqueSaveValue), ';');

            $responseFile = $this->offlineModeDir.'/responses/'.$nextResponse.'.txt';
            fclose(fopen($responseFile, 'a+'));

            /** @var Response $response */
            $response = $communicator->post($request);
            file_put_contents($responseFile, $response->getResponseString());

            $this->closeRequestHandle();

            return $response->getResponseString();
        }

        if ($this->isResponseStored($uniqueSaveValue) === true) {
            $requests = file($this->offlineModeDir.'/requests.csv');

            foreach ($requests as $line) {
                $requestLine = preg_split('#;#', $line);

                if (trim($requestLine[1]) === $uniqueSaveValue) {
                    $responseFile = $this->offlineModeDir.'/responses/'.$requestLine[0].'.txt';

                    $stringResponse = file_get_contents($responseFile);

                    $this->closeRequestHandle();

                    return $stringResponse;
                }
            }
        }

        $message = sprintf(
            'There is a possible bug in EbayOfflineMode. Please, fix it'
        );

        throw new \RuntimeException($message);
    }
    /**
     * @param string $uniqueValue
     * @return bool
     */
    public function isResponseStored(string $uniqueValue) : bool
    {
        $requests = file($this->offlineModeDir.'/requests.csv');

        foreach ($requests as $line) {
            $requestLine = preg_split('#;#', $line);

            if (trim($requestLine[1]) === $uniqueValue) {
                return true;
            }
        }

        return false;
    }

    private function closeRequestHandle(): void
    {
        if (is_resource($this->requestHandle)) {
            fclose($this->requestHandle);
        }
    }
}