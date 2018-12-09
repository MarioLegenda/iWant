<?php

namespace App\Library\Slack;

use App\Library\Util\Util;

class Config
{
    /**
     * @var array $config
     */
    private $config = [];
    /**
     * @var array $configNormalized
     */
    private $configNormalized = [];
    /**
     * Config constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        $this->normalizeConfig();
    }
    /**
     * @param string $channel
     * @return string
     */
    public function getWebhook(string $channel): string
    {
        if (!$this->hasChannel($channel)) {
            $message = sprintf(
                'Channel %s does not exist',
                $channel
            );

            throw new \RuntimeException($message);
        }

        return $this->configNormalized[$channel];
    }

    public function hasChannel(string $channel): bool
    {
        return array_key_exists($channel, $this->configNormalized);
    }
    /**
     * @throws \RuntimeException
     */
    private function normalizeConfig(): void
    {
        $webhooksMap = $this->config['webhooksMap'];
        $webhooksMapGen = Util::createGenerator($webhooksMap);

        foreach ($webhooksMapGen as $entry) {
            $item = $entry['item'];

            if (!array_key_exists('channel', $item) OR !array_key_exists('webhook', $item)) {
                $message = sprintf(
                    'Slack config invalid. \'channel\' or \'webhook\' key missing'
                );

                throw new \RuntimeException($message);
            }

            $channel = $item['channel'];
            $webhook = $item['webhook'];

            $this->configNormalized[$channel] = $webhook;
        }
    }
}