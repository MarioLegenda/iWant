<?php

namespace App\Amazon\Library\Processor\Signature;

class ParametersSplitProcessor implements SignatureProcessorInterface
{
    /**
     * @param SignatureData $data
     * @return SignatureData
     */
    public function process(SignatureData $data): SignatureData
    {
        $encoded = $data->get(UrlEncodeProcessor::class);

        $hostSplit = explode('?', $encoded)[1];
        $restOfTheStringSplitted = explode('&', $hostSplit);

        $deletedAmpersand = [];

        foreach($restOfTheStringSplitted as $item) {
            $deletedAmpersand[] = rtrim($item, '&');
        }

        $data->add(get_class($this), array_values($deletedAmpersand));

        return $data;
    }
}