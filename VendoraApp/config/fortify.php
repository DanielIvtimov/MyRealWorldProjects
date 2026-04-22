<?php

use Laravel\Fortify\Features;

return [
    'guard' => 'customer',
    'passwords' => 'customers',
    'username' => 'email',
    'email' => 'email',
    'lowercase_usernames' => true,
    'home' => '/my-account',
    'prefix' => '',
    'domain' => null,
    'middleware' => ['web'],
    'limiters' => [
        'login' => 'login',
    ],
    'views' => true,
    'features' => [
        Features::registration(),
        Features::resetPasswords(),
    ],
];
