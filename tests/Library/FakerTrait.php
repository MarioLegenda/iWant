<?php

namespace App\Tests\Library;

use Faker\Factory;
use Faker\Generator;

trait FakerTrait
{
    /**
     * @var Factory $faker
     */
    private $faker;
    /**
     * @return Generator
     */
    public function faker(): Generator
    {
        if (!$this->faker instanceof Factory) {
            $this->faker = Factory::create();
        }

        return $this->faker;
    }
}