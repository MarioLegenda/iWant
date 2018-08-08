<?php

namespace App\Amazon\Library\Processor\Signature;


class FinalProcessor implements SignatureProcessorInterface
{
    /**
     * @param SignatureData $data
     * @return SignatureData
     */
    public function process(SignatureData $data): SignatureData
    {
        $sorted = $data->get(SortProcessor::class);
        $hmacEncoded = $data->get(HMACEncoder::class);
        $host = $data->get('host');

        $host.='?';
        foreach ($sorted as $name => $item) {
            $host.=$name.'='.$item.'&';
        }

        $host = rtrim($host, '&');

        $host.='&Signature='.$hmacEncoded;

        $data->add(FinalProcessor::class, $host);

        return $data;
    }
}