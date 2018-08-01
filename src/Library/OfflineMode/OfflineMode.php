<?php

namespace App\Library\OfflineMode;

use App\Ebay\Source\GenericHttpCommunicator;
use App\Library\OfflineMode\OfflineModeMetadata;

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
     * @param string $url
     * @return OfflineMode
     */
    public static function inst(string $url)
    {
        static::$instance = (!static::$instance instanceof static) ? new OfflineMode($url) : static::$instance;

        return static::$instance;
    }
    /**
     * EbayOfflineMode constructor.
     * @param string $url
     */
    private function __construct(string $url)
    {
        $this->url = $url;
        $this->offlineModeDir = OfflineModeMetadata::getOfflineModeDirectory();

        $this->requestHandle = fopen($this->offlineModeDir.'/requests.csv', 'a+');

        if (!file_exists($this->offlineModeDir.'/responses')) {
            mkdir($this->offlineModeDir.'/responses');
        }
    }
    /**
     * @param GenericHttpCommunicator $communicator
     * @return string
     */
    public function getResponse(GenericHttpCommunicator $communicator): string
    {
        if (!$this->isResponseStored()) {
            $requests = file($this->offlineModeDir.'/requests.csv');

            // if requests.csv is empty, fill it with first request
            if (empty($requests)) {
                // add a request to requests.csv
                fputcsv($this->requestHandle, array(1, $this->url), ';');
                $responseFile = $this->offlineModeDir.'/responses/1.txt';
                fclose(fopen($this->offlineModeDir.'/responses/1.txt', 'a+'));

                // makes a request and adds the response to newly created response file
                $stringResponse = $communicator->get($this->url);
                file_put_contents($responseFile, $stringResponse);

                fclose($this->requestHandle);

                return $stringResponse;
            }

            $lastRequest = preg_split('#;#', array_pop($requests));

            $nextResponse = (int) ++$lastRequest[0];

            fputcsv($this->requestHandle, array($nextResponse, $this->url), ';');

            $responseFile = $this->offlineModeDir.'/responses/'.$nextResponse.'.txt';
            fclose(fopen($responseFile, 'a+'));

            $stringResponse = $communicator->get($this->url);
            file_put_contents($responseFile, $stringResponse);

            fclose($this->requestHandle);

            return $stringResponse;
        }

        if ($this->isResponseStored() === true) {
            $requests = file($this->offlineModeDir.'/requests.csv');

            foreach ($requests as $line) {
                $requestLine = preg_split('#;#', $line);

                if (trim($requestLine[1]) === $this->url) {
                    $responseFile = $this->offlineModeDir.'/responses/'.$requestLine[0].'.txt';

                    $stringResponse = file_get_contents($responseFile);

                    fclose($this->requestHandle);

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
     * @return bool
     */
    public function isResponseStored() : bool
    {
        $requests = file($this->offlineModeDir.'/requests.csv');

        foreach ($requests as $line) {
            $requestLine = preg_split('#;#', $line);

            if (trim($requestLine[1]) === $this->url) {
                return true;
            }
        }

        return false;
    }
}