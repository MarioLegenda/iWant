<?php

namespace App\Amazon\Library\Processor\Signature;

class UrlEncodeProcessor implements SignatureProcessorInterface
{
    /**
     * @var array $signs
     */
    private $signs;
    /**
     * UrlEncodeProcessor constructor.
     * @param array $signs
     */
    public function __construct(array $signs)
    {
        $this->signs = $signs;
    }
    /**
     * @param SignatureData $data
     * @return string
     */
    public function process(SignatureData $data)
    {
        $url = $data->get('url');

        $splitted = explode('?', $url);
        $host = $splitted[0];
        $withoutHost = $splitted[1];
        $splitted = explode('&', $withoutHost);

        $params = [];
        foreach ($splitted as $part) {
            $values = explode('=', $part);

            $name = $values[0];
            $value = $values[1];

            foreach ($this->signs as $sign => $replacement) {
                $value = str_replace(',', '%2C', $value);
                $value = str_replace(':', '%3A', $value);
            }

            $params[$name] = $value;
        }

        $host.='?';
        foreach ($params as $name => $value) {
            $host.=$name.'='.$value.'&';
        }

        $data->add(get_class($this), rtrim($host, '&'));

        return $data;
    }
}