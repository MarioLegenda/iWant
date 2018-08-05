<?php

namespace App\Amazon\Library\Processor\Signature;

class ParametersSplitProcessor implements SignatureProcessorInterface
{
    /**
     * @param $data
     * @return array
     */
    public function process(SignatureData $data)
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