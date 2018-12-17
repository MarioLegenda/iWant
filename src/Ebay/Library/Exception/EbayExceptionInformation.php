<?php

namespace App\Ebay\Library\Exception;

use App\Ebay\Library\Response\ResponseModelInterface as EbayResponseModelInterface;
use App\Library\Http\Request;
use App\Library\Http\Response\ResponseModelInterface;

class EbayExceptionInformation
{
    /**
     * @var ResponseModelInterface $response
     */
    private $response;
    /**
     * @var EbayResponseModelInterface $responseModel
     */
    private $responseModel;
    /**
     * @var string $type
     */
    private $type;
    /**
     * @var Request $request
     */
    private $request;
    /**
     * EbayExceptionInformation constructor.
     * @param ResponseModelInterface $response
     * @param EbayResponseModelInterface $responseModel
     * @param string $type
     * @param Request $request
     */
    public function __construct(
        Request $request,
        string $type,
        ResponseModelInterface $response,
        EbayResponseModelInterface $responseModel
    ) {
        $this->response = $response;
        $this->responseModel = $responseModel;
        $this->type = $type;
        $this->request = $request;
    }
    /**
     * @return ResponseModelInterface
     */
    public function getResponse(): ResponseModelInterface
    {
        return $this->response;
    }
    /**
     * @return EbayResponseModelInterface
     */
    public function getEbayResponseModel(): EbayResponseModelInterface
    {
        return $this->responseModel;
    }
    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}