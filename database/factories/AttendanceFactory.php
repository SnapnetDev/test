<?php

use Faker\Generator as Faker;

$factory->define(App\Attendance::class, function (Faker $faker) {
    return [
        'emp_num' => 109,
        'date' => '2018-07-24',
        'shift_id' => '1', 
    ];
});
