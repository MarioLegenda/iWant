<?php

namespace App\Tests\Library;

use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BasicSetup extends WebTestCase
{
    use FakerTrait;
    /**
     * @var Client $client
     */
    protected $client;
    /**
     * @var ContainerInterface $container
     */
    protected $locator;

    protected function setUp()
    {
        $this->client = static::createClient();

        $this->locator = $this->client->getContainer();
    }
    /**
     * @param $expected
     * @param $value
     * @param string $message
     */
    protected function assertInternalTypeOrNull($expected, $value, $message = '')
    {
        $method = sprintf ('is_%s', $expected);

        $result = call_user_func($method, $value);

        if ($result === false) {
            if (!is_null($value)) {
                $message = sprintf(
                    'Failed asserting that %s is of type %s or null',
                    gettype($value),
                    gettype($expected)
                );

                $this->fail($message);
            }
        }
    }
}