<?php

return [
    'name' => 'AuthSaml2',
    'login-callback' => env('FRONTEND_URL') . '/auth/callback',
    'reset-password' => env('FRONTEND_URL') . '/auth/new-password',
    'blocked-access' => env('FRONTEND_URL') . '/auth/blocked-access',
    'auth' => env('FRONTEND_URL') . '/auth',
    'login' => env('FRONTEND_URL')
];
