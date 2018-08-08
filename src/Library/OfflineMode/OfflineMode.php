<?php

namespace App\Library\OfflineMode;

use App\Ebay\Source\GenericHttpCommunicator;
use App\Library\Http\GenericHttpCommunicatorInterface;
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
     * @param string $url
     * @return string
     */
    public function getResponse(
        GenericHttpCommunicatorInterface $communicator,
        string $url
    ): string {
        $this->url = $url;
        $this->requestHandle = fopen($this->offlineModeDir.'/requests.csv', 'a+');

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

                $this->closeRequestHandle();

                return $stringResponse;
            }

            $lastRequest = preg_split('#;#', array_pop($requests));

            $nextResponse = (int) ++$lastRequest[0];

            fputcsv($this->requestHandle, array($nextResponse, $this->url), ';');

            $responseFile = $this->offlineModeDir.'/responses/'.$nextResponse.'.txt';
            fclose(fopen($responseFile, 'a+'));

            $stringResponse = $communicator->get($this->url);
            file_put_contents($responseFile, $stringResponse);

            $this->closeRequestHandle();

            return $stringResponse;
        }

        if ($this->isResponseStored() === true) {
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

    private function closeRequestHandle(): void
    {
        if (is_resource($this->requestHandle)) {
            fclose($this->requestHandle);
        }
    }
}