<?php

namespace App\Amazon\Library\Processor\Signature;

class LineBreakProcessor implements SignatureProcessorInterface
{
    /**
     * @param string $data
     * @return mixed|string
     */
    public function process(SignatureData $data)
    {
        $rejoinedAmpersand = $data->get(RejoinAmpersandProcessor::class);
        $parsedUrl = parse_url($data->get('url'));

        $data->add(LineBreakProcessor::class, sprintf(
            "GET\n%s\n%s\n%s",
            $parsedUrl['host'],
            $parsedUrl['path'],
            $rejoinedAmpersand
        ));

        return $data;
    }
}