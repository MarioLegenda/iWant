<?php

namespace App\Amazon\Library\Processor\Signature;

class SortProcessor implements SignatureProcessorInterface
{
    /**
     * @param SignatureData $data
     * @return SignatureData
     */
    public function process(SignatureData $data): SignatureData
    {
        $previous = $data->get(ParametersSplitProcessor::class);

        $params = [];
        foreach ($previous as $item) {
            $values = explode('=', $item);

            $params[$values[0]] = $values[1];
        }

        ksort($params);

        $data->add(SortProcessor::class, $params);

        return $data;
    }
}