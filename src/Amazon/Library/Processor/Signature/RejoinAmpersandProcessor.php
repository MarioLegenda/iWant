<?php

namespace App\Amazon\Library\Processor\Signature;

class RejoinAmpersandProcessor implements SignatureProcessorInterface
{
    /**
     * @param SignatureData $data
     * @return SignatureData
     */
    public function process(SignatureData $data): SignatureData
    {
        $previous = $data->get(SortProcessor::class);

        $url = '';
        foreach ($previous as $key => $item) {
            $url.=$key.'='.$item.'&';
        }

        $data->add(RejoinAmpersandProcessor::class, rtrim($url, '&'));

        return $data;
    }
}