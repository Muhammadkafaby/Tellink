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
            // Use /api/projects endpoint which is working
            \Log::info('Fetching projects from: ' . $this->apiBaseUrl . '/api/projects');
            
            $response = Http::timeout(30)->get($this->apiBaseUrl . '/api/projects');
            
            \Log::info('API Response Status: ' . $response->status());
            
            if ($response->successful()) {
                $responseData = $response->json();
                \Log::info('Projects API Response:', ['data' => $responseData]);
                
                // Check if response has success structure
                if (isset($responseData['success']) && $responseData['success'] && isset($responseData['data'])) {
                    // Transform API response to match our frontend structure
                    $posts = array_map(function($post) {
                        // Convert Firebase timestamp to date
                        $date = date('Y-m-d');
                        if (isset($post['createdAt']['seconds'])) {
                            $date = date('Y-m-d', $post['createdAt']['seconds']);
                        }
                        
                        return [
                            'id' => $post['id'] ?? uniqid(),
                            'nim' => $post['nim'] ?? '',
                            'date' => $date,
                            'desc' => $post['desc'] ?? '',
                            'images' => $post['image'] ?? null,
                            'likes' => $post['likes'] ?? 0,
                            'title' => $post['title'] ?? ''
                        ];
                    }, $responseData['data']);
                    
                    return response()->json($posts);
                }
                
                // If data structure is different, return as is
                return response()->json($responseData);
            }
            
            // Log error and return empty array
            \Log::error('Failed to fetch projects', [
                'status' => $response->status(),
                'body' => substr($response->body(), 0, 500)
            ]);
            
            return response()->json([]);
        } catch (\Exception $e) {
            \Log::error('Error fetching projects: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    public function createMessage(Request $request)
    {
        try {
            // According to API docs, use /api/addproject with multipart/form-data
            $response = Http::asMultipart()
                ->attach('image', $request->get('images', ''), 'image.jpg')
                ->post($this->apiBaseUrl . '/api/addproject', [
                    'nim' => $request->get('nim'),
                    'title' => $request->get('title'),
                    'description' => $request->get('desc'),
                    'requirements' => $request->get('requirements', 'General')
                ]);
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['error' => 'Failed to create project'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateMessage(Request $request, $id)
    {
        try {
            // According to API docs, use /api/editproject with multipart/form-data
            $response = Http::asMultipart()
                ->attach('image', $request->get('images', ''), 'image.jpg')
                ->post($this->apiBaseUrl . '/api/editproject', [
                    'projectId' => $id,
                    'nim' => $request->get('nim'),
                    'title' => $request->get('title'),
                    'description' => $request->get('desc'),
                    'requirements' => $request->get('requirements', 'General')
                ]);
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['error' => 'Failed to update project'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteMessage($id)
    {
        try {
            // According to API docs, use /api/deleteproject
            $response = Http::post($this->apiBaseUrl . '/api/deleteproject', [
                'projectId' => $id,
                'nim' => request()->get('nim', auth()->user()->nim ?? '')
            ]);
            
            if ($response->successful()) {
                return response()->json(['success' => true]);
            }
            
            return response()->json(['error' => 'Failed to delete project'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
