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

    /**
     * @param $expected
     * @param $value
     * @param string $message
     */
    protected function assertInstanceOfOrNull($expected, $value, $message = '')
    {
        if (!class_exists($expected)) {
            $message = sprintf(
                'Class %s does not exist in assertion method %s',
                $expected,
                __FUNCTION__
            );

            throw new \RuntimeException($message);
        }

        if (!is_object($value) and !is_null($value)) {
            $message = sprintf(
                'Value to be asserted is not an object and is not null in assertion method %s',
                __FUNCTION__
            );

            throw new \RuntimeException($message);
        }

        if (is_null($value)) {
            return;
        }

        $result = $expected === get_class($value);

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
    /**
     * @param $v1
     * @param $v2
     * @param string $message
     */
    protected function assertLowerThanOrEquals($v1, $v2, string $message = '%s  is not lower than %s')
    {
        if ($v1 >= $v2) {
            return;
        }

        $this->fail(sprintf($message, $v1, $v2));
    }
    /**
     * @param $v1
     * @param $v2
     * @param string $message
     */
    protected function assertGreaterThanOrEquals($v1, $v2, string $message = '%s  is not lower than %s')
    {
        if ($v1 <= $v2) {
            return;
        }

        $this->fail(sprintf($message, $v1, $v2));
    }
}