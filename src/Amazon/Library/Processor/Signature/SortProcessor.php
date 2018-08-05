<?php

namespace App\Amazon\Library\Processor\Signature;

class SortProcessor implements SignatureProcessorInterface
{
    /**
     * @param $data
     * @return array
     */
    public function process(SignatureData $data)
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