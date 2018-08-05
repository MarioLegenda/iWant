<?php

namespace App\Amazon\Library\Processor\Signature;

interface SignatureProcessorInterface
{
    /**
     * @param SignatureData $data
     * @return mixed
     */
    public function process(SignatureData $data);
}