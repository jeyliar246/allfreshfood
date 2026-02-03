<?php
return [
    'stripe_pk' => env('STRIPE_TEST_PK'),
    'secret' => env('STRIPE_TEST_SK'),
    'currency' => env('STRIPE_CURRENCY', 'gbp'),
];