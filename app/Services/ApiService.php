<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiService
{
    protected $baseUrl;
    protected $timeout;
    
    public function __construct()
    {
        $this->baseUrl = config('tellink.api_base_url');
        $this->timeout = config('tellink.api_timeout');
    }
    
    protected function request($method, $uri, $options = [])
    {
        $url = $this->baseUrl . $uri;
        
        try {
            $response = Http::timeout($this->timeout)
                ->retry(config('tellink.api_retry_times'), config('tellink.api_retry_delay'))
                ->$method($url, $options);
            
            return $response->throw()->json();
        } catch (\Exception $e) {
            Log::error("API request failed: {$e->getMessage()}");
            return null;
        }
    }
}

