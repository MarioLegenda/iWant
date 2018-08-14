<?php

namespace App\Bonanza\Source;

use App\Library\Http\Request;
use App\Bonanza\Source\Repository\BonanzaRepository;
use App\Library\Response;
use App\Symfony\Exception\HttpException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;

class FinderSource
{
    /**
     * @var BonanzaRepository $bonanzaRepository
     */
    private $bonanzaRepository;
    /**
     * FinderSource constructor.
     * @param BonanzaRepository $bonanzaRepository
     */
    public function __construct(
        BonanzaRepository $bonanzaRepository
    ) {
        $this->bonanzaRepository = $bonanzaRepository;
    }

    public function getResource(Request $request): string
    {
        /** @var Response $response */
        $response = $this->bonanzaRepository->getResource($request);

        return $response->getResponseString();
    }
}