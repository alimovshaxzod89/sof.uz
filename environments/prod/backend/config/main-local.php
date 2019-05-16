<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => getenv('BACKEND_COOKIE_VALIDATION_KEY'),
        ],
    ],
];

return $config;
