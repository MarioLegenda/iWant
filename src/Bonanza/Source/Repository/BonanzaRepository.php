<?php

namespace App\Bonanza\Source\Repository;

use App\Library\Http\Request;
use App\Library\Http\GenericHttpCommunicatorInterface;
use App\Library\OfflineMode\OfflineMode;
use App\Library\Response;

class BonanzaRepository
{
    /**
     * @var string $env
     */
    private $env;
    /**
     * @var GenericHttpCommunicatorInterface $communicator
     */
    private $communicator;
    /**
     * BonanzaRepository constructor.
     * @param GenericHttpCommunicatorInterface $communicator
     * @param string $env
     */
    public function __construct(
        string $env,
        GenericHttpCommunicatorInterface $communicator
    ) {
        $this->env = $env;
        $this->communicator = $communicator;
    }
    /**
     * @param Request $request
     * @return Response
     */
    public function getResource(Request $request): Response
    {
        if ($this->env === 'dev' or $this->env === 'test') {
            $responseString = OfflineMode::inst()->getPostResponse(
                $request,
                $this->createUniqueValueForOfflineModeFromRequest($request),
                $this->communicator
            );

            return new Response(
                $request,
                $responseString,
                200
            );
        }

        return $this->communicator->post($request);
    }
    /**
     * @param Request $request
     * @return string
     */
    private function createUniqueValueForOfflineModeFromRequest(Request $request): string
    {
        $baseUrl = $request->getBaseUrl();
        $headers = $request->getHeaders();

        foreach ($headers as $headerName => $header) {
            $baseUrl.=$headerName.$header;
        }

        $normalizedData = explode('=', $request->getData());

        $normalizedDataString = '';
        if ($normalizedData[0] === 'findItemsByKeywords') {
            $decodedData = json_decode($normalizedData[1], true);

            foreach ($decodedData as $name => $value) {
                if (is_array($value)) {
                    foreach ($value as $valueName => $valueValue) {
                        $normalizedDataString.=$valueName.$valueValue;
                    }

                    continue;
                }

                if (is_string($value)) {
                    $normalizedDataString.=$name.$value;
                }
            }
        }

        $normalizedDataString = preg_replace('#\s+#','', $normalizedDataString);
        $normalizedDataString = preg_replace('#,#', '', $normalizedDataString);
        $baseUrl.=$normalizedDataString;

        return $baseUrl;
    }
}