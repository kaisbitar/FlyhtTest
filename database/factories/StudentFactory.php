<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Student::class, function (Faker $faker) {
    return [
        'firstName' => $faker->firstName,
        'lastName'  => $faker->lastName(30),
        'phone'  => $faker->phoneNumber,
        'status'  => $faker->randomElement(['active', 'delinquent', 'dropped'])
    ];
});
