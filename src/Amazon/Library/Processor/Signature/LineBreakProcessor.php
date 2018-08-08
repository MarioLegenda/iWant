<?php

namespace App\Amazon\Library\Processor\Signature;

class LineBreakProcessor implements SignatureProcessorInterface
{
    /**
     * @param SignatureData $data
     * @return SignatureData
     */
    public function process(SignatureData $data): SignatureData
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