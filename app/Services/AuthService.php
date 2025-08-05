<?php

namespace App\Services;

class AuthService extends ApiService
{
    public function register($data)
    {
        return $this->request('post', '/api/auth/register', ['json' => $data]);
    }

    public function login($data)
    {
        return $this->request('post', '/api/auth/login', ['json' => $data]);
    }

    public function getUserProfile($token)
    {
        return $this->request('get', '/api/auth/me', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);
    }
}

