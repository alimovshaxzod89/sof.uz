<?php
return [
    'adminEmail'                         => 'admin@activemedia.com',
    'supportEmail'                       => 'shop@activemedia.uz',
    'user.passwordResetTokenExpire'      => 3600,
    'admin.passwordResetTokenExpire'     => 3600,
    'developer.passwordResetTokenExpire' => 3600,
    'staticDomain'                       => getenv('STATIC_URL'),
    'staticUrl'                          => getenv('STATIC_URL'),
    'user.loginDuration'                 => 86400,
];
