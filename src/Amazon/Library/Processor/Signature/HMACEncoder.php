<?php

namespace App\Amazon\Library\Processor\Signature;

class HMACEncoder implements SignatureProcessorInterface
{
    /**
     * @var string $signsToEncode
     */
    private $signsToEncode;
    /**
     * HMACEncoder constructor.
     * @param array $signsToEncode
     */
    public function __construct(array $signsToEncode)
    {
        $this->signsToEncode = $signsToEncode;
    }
    /**
     * @param $data
     * @return mixed|string
     */
    public function process(SignatureData $data)
    {
        $lineBreakData = $data->get(LineBreakProcessor::class);
        $privateKey = $data->get('private_key');

        $encoded = base64_encode(hash_hmac("sha256", $lineBreakData, $privateKey, true));

        foreach ($this->signsToEncode as $name => $value) {
            $encoded = str_replace($name, $value, $encoded);
        }

        $data->add(HMACEncoder::class, $encoded);

        return $data;
    }
}