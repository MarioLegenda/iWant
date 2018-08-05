<?php

namespace App\Amazon\Library\Processor\Signature;

class RejoinAmpersandProcessor implements SignatureProcessorInterface
{
    /**
     * @param $data
     * @return string
     */
    public function process(SignatureData $data)
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