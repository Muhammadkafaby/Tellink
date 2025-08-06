<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tellink Backend API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for connecting to the Tellink Backend API
    |
    */

    'api_base_url' => env('TELLINK_API_URL', 'https://tellink-backend-2-b916417fa9aa.herokuapp.com'),
    
    'api_timeout' => env('TELLINK_API_TIMEOUT', 30),
    
    'api_retry_times' => env('TELLINK_API_RETRY_TIMES', 3),
    
    'api_retry_delay' => env('TELLINK_API_RETRY_DELAY', 100),
];
