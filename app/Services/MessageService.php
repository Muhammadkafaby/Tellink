<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class MessageService extends ApiService
{
    private function getToken()
    {
        return Session::get('token');
    }

    public function getAllMessages()
    {
        return $this->request('get', '/api/messages', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getToken()
            ]
        ]);
    }

    public function getMessageById($id)
    {
        return $this->request('get', '/api/messages/' . $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getToken()
            ]
        ]);
    }

    public function sendMessage($data)
    {
        return $this->request('post', '/api/messages', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getToken()
            ],
            'json' => $data
        ]);
    }

    public function updateMessage($id, $data)
    {
        return $this->request('put', '/api/messages/' . $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getToken()
            ],
            'json' => $data
        ]);
    }

    public function deleteMessage($id)
    {
        return $this->request('delete', '/api/messages/' . $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getToken()
            ]
        ]);
    }
}
