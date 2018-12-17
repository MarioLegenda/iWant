<?php

namespace App\Library\Exception;

class HttpTransferException extends \Exception implements HttpExceptionInterface
{
    /**
     * @var TransferExceptionInformationWrapper $transferExceptionInformation
     */
    private $transferExceptionInformation;
    /**
     * HttpTransferException constructor.
     * @param TransferExceptionInformationWrapper $transferExceptionInformationWrapper
     */
    public function __construct(TransferExceptionInformationWrapper $transferExceptionInformationWrapper)
    {
        parent::__construct($transferExceptionInformationWrapper->getBody());

        $this->transferExceptionInformation = $transferExceptionInformationWrapper;
    }
    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->transferExceptionInformation->getBody();
    }
    /**
     * @return TransferExceptionInformationWrapper
     */
    public function getTransferInformationWrapper(): TransferExceptionInformationWrapper
    {
        return $this->transferExceptionInformation;
    }
}