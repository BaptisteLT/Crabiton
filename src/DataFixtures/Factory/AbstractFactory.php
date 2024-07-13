<?php

namespace App\DataFixtures\Factory;

use Faker\Factory;
use Faker\Generator;

abstract class AbstractFactory
{
    protected Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }
}
