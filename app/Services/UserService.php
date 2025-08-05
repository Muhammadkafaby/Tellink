<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class UserService extends ApiService
{
    private function getToken()
    {
        return Session::get('token');
    }

    public function getAllUsers()
    {
        return $this->request('get', '/api/users', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getToken()
            ]
        ]);
    }

    public function getUserById($id)
    {
        return $this->request('get', '/api/users/' . $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getToken()
            ]
        ]);
    }

    public function updateUser($id, $data)
    {
        return $this->request('put', '/api/users/' . $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getToken()
            ],
            'json' => $data
        ]);
    }

    public function deleteUser($id)
    {
        return $this->request('delete', '/api/users/' . $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getToken()
            ]
        ]);
    }

    public function uploadAvatar($file)
    {
        return $this->request('post', '/api/users/upload', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getToken()
            ],
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($file->getPathname(), 'r'),
                    'filename' => $file->getClientOriginalName()
                ]
            ]
        ]);
    }
}
