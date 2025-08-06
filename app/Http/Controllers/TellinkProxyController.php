<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TellinkProxyController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('tellink.api_base_url');
    }

    public function getUsers()
    {
        try {
            // Get mahasiswa list from API
            $response = Http::get($this->apiBaseUrl . '/api/mahasiswa');
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['error' => 'Failed to fetch users'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function registerUser(Request $request)
    {
        try {
            $response = Http::post($this->apiBaseUrl . '/api/register', $request->all());
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['error' => 'Failed to register user'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function deleteUser(Request $request)
    {
        try {
            $response = Http::post($this->apiBaseUrl . '/api/deletemahasiswa', $request->all());
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['error' => 'Failed to delete user'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getMessages()
    {
        try {
            $response = Http::get($this->apiBaseUrl . '/api/messages');
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['error' => 'Failed to fetch messages'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createMessage(Request $request)
    {
        try {
            $response = Http::post($this->apiBaseUrl . '/api/messages', $request->all());
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['error' => 'Failed to create message'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateMessage(Request $request, $id)
    {
        try {
            $response = Http::put($this->apiBaseUrl . '/api/messages/' . $id, $request->all());
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['error' => 'Failed to update message'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteMessage($id)
    {
        try {
            $response = Http::delete($this->apiBaseUrl . '/api/messages/' . $id);
            
            if ($response->successful()) {
                return response()->json(['success' => true]);
            }
            
            return response()->json(['error' => 'Failed to delete message'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
