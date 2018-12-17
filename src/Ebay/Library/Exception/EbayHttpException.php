<?php

namespace App\Ebay\Library\Exception;

use App\Library\Exception\HttpExceptionInterface;

class EbayHttpException extends BaseEbayException implements HttpExceptionInterface
{
    /**
     * @var EbayExceptionInformation $ebayExceptionInformation
     */
    private $ebayExceptionInformation;
    /**
     * EbayHttpException constructor.
     * @param EbayExceptionInformation $ebayExceptionInformation
     */
    public function __construct(EbayExceptionInformation $ebayExceptionInformation)
    {
        parent::__construct($ebayExceptionInformation->getResponse()->getBody());
    }
    /**
     * @return EbayExceptionInformation
     */
    public function getEbayExceptionInformation(): EbayExceptionInformation
    {
        return $this->ebayExceptionInformation;
    }
    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->ebayExceptionInformation->getResponse()->getBody();
    }
}