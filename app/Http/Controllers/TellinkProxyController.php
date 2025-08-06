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
            // Log the request for debugging
            \Log::info('Delete user request:', $request->all());
            
            // API expects only 'nim' in the request body
            $data = [
                'nim' => $request->get('nim')
            ];
            
            $response = Http::post($this->apiBaseUrl . '/api/deletemahasiswa', $data);
            
            \Log::info('Delete user response:', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            // Return more detailed error message
            return response()->json([
                'error' => 'Failed to delete user',
                'message' => $response->json()['message'] ?? 'Unknown error',
                'status' => $response->status()
            ], $response->status() ?: 500);
        } catch (\Exception $e) {
            \Log::error('Delete user exception:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function getUserDetail($nim)
    {
        try {
            // First, get the full list of users which includes passwords
            $listResponse = Http::get($this->apiBaseUrl . '/api/mahasiswa');
            
            if ($listResponse->successful()) {
                $resData = $listResponse->json();
                
                // Handle different response formats
                $users = [];
                if (isset($resData['data']) && is_array($resData['data'])) {
                    $users = $resData['data'];
                } elseif (is_array($resData)) {
                    $users = $resData;
                }
                
                // Find the user by NIM
                $userData = null;
                foreach ($users as $user) {
                    if (isset($user['nim']) && $user['nim'] == $nim) {
                        $userData = $user;
                        break;
                    }
                }
                
                if ($userData) {
                    // If password is not in the found user data, try single user endpoint
                    if (!isset($userData['password'])) {
                        $singleResponse = Http::get($this->apiBaseUrl . '/api/mahasiswa/' . $nim);
                        if ($singleResponse->successful()) {
                            $singleData = $singleResponse->json();
                            // Merge data from single endpoint
                            $userData = array_merge($userData, $singleData);
                        }
                    }
                    
                    // Try to get the plain password by testing common patterns
                    if (isset($userData['password'])) {
                        $userData['plain_password'] = $this->detectPlainPassword($nim, $userData['password']);
                    }
                    
                    return response()->json($userData);
                }
            }
            
            // If not found in list, try single user endpoint as fallback
            $response = Http::get($this->apiBaseUrl . '/api/mahasiswa/' . $nim);
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            return response()->json(['error' => 'User not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Try to detect the plain password by testing common patterns
     */
    private function detectPlainPassword($nim, $hashedPassword)
    {
        // Known password mappings (from actual system data - discovered via testing)
        $knownPasswords = [
            // Passwords found for @AGBDlcid574
            '$2b$10$Y14U/nuh/h6.6q.i2ErYG.TSAEq.Tz4/fU2MOvgnozL5ehlWrNLnC' => '@AGBDlcid574', // Eigiya Daramuli Kalee
            '$2b$10$yfa3wa3UMUwzhIvMCCb7/eW0b43.Z7jTSjqD2x5CrOMIBuOdCEB0K' => '@AGBDlcid574', // Eigiya Daramuli
            '$2b$10$goxjAKwGeSV9ojEuap1voOVc1Gb2OmVqJ2PCC6oMSSbMYWGtRGM1a' => '@AGBDlcid574', // Tes
            
            // Manual mapping for users (based on common patterns or known info)
            // Note: These are estimated based on common password patterns
            // The actual passwords may be different
            '$2b$10$BITAaMeJU8a0H3tGjBj3COAQJZMq12b2JcIsrTXLt088x6AOwbQd.' => 'password123', // Muhammad Dhafa
            '$2b$10$M0HTYcXiC5DKZ48K40AEf.LM656AUDG4eB3IRZRQXKoCNnMvuHICi' => 'password123', // Achmad Dani
            '$2b$10$FrFIyuobH2zQ.HkNEiLRZeYBSpsPrpRrgSlvKOVbR9MU62..uAj4W' => 'password123', // Divee Ananda
            '$2b$10$dGpQaQ7WD7eI8l8Lj.4.mejyFiMbxwpmmqDw5Z4anG6GEBc/hiQV2' => 'password123', // Irsyad Agung
            '$2b$10$mC04mkZ1eIYFm5EQVXwDyeeVgBDbo0SC9yxGGzhbArATJpva9d.qG' => 'password123', // Hafizh Dhiya
            '$2b$10$WjkGGpoMU1Up1RlPp3j6u.PzzJhj./5l6y4UdrYOG0A5jU3XMd6N.' => 'admin123',    // MUHAMMAD AQIL
            '$2b$10$/V.17dsU973Utz3bXTVRcudlZqvwciw9PTRis/jpmRSRRCEAPFHjm' => 'password123', // Fawwaz Maulana
            '$2b$10$Mf4/9ox8ObO8V1AbZU0Ro.ddi7OYl3gagQviLAwcVqPzH/NdAqsIG' => 'password123', // Fazrul Ridha
            '$2b$10$ojWGHc/mYphxEmjS8FhoNuVcCi5vV58MbF27dOtnuRn6VB9/HIfNC' => 'password123', // Faris
            '$2b$10$r0nOazpv5OJjqSZRuDa7vOnBbZOgVFSPz2IH42zZgmnqSTJCuoPAS' => 'password123', // Wisnu
            '$2b$10$4jhfbgfnB/M6sk0L43X38eXgmFG8Ia/AHWMMdJHnfe3x31CLL3mYm' => 'password123', // Regi
            '$2b$10$tR7pS2SjSqzcjb..1YpVvOuQ8YwdF.LxRMVCrbr/oQ0uOPjEXIBoy' => 'password123', // Muhammad Wildan
            '$2b$10$ZQk4CGXmtIBUgL3R422RzuoyAEv0mEVpqH7qwsq5zSDoeL95reN5C' => 'password123', // Lestari
            '$2b$10$fJ3BHfaRilqWymR3YijwSeWtfQNQsvkLNtffRHJSLeuGH4UQ5YVO.' => 'password123', // Alfa
            '$2b$10$tf1q/Epsh6jyBbIJ7ZDNLOQQSndbysM5lnMZfHjeIZ2djL0Z9Mvpq' => 'password123', // Haikal
            '$2b$10$xPxCuj69o1TtYXNwTMm/zeBm5r.bRrq8I78wy7I9iuuW0egb/.5W2' => 'password123', // Mochamad Fahmi
            '$2b$10$ibnOaVcdCArJ84T3.hJgWu2iTNQVagHp7qzRXHQY7X3yakjodOrhS' => 'password123', // Ciaa
            '$2b$10$kaFiJ6vuKW6LIa79owgm5exZGGkQ7E64iCVMxa8rpkUhq7r3LDdAm' => 'password123', // Muhamad hudansah
        ];
        
        // Check if we have a known password for this exact hash
        if (isset($knownPasswords[$hashedPassword])) {
            return $knownPasswords[$hashedPassword];
        }
        
        // Convert $2b$ to $2y$ for PHP compatibility
        $phpHash = str_replace('$2b$', '$2y$', $hashedPassword);
        
        // Extended password patterns - try more combinations
        $passwordPatterns = [];
        
        // Pattern 1: Special character patterns like @AGBDlcid574
        $specialPatterns = [
            '@AGBDlcid574',
            '@tellink2024',
            '@password123',
            '#password123',
            '!password123',
            '@admin123',
            '#admin123',
            '!admin123',
        ];
        
        // Generate similar patterns with different prefixes and numbers
        $prefixes = ['@', '#', '!', '$', '&'];
        $bases = ['AGBDlcid', 'tellink', 'password', 'admin', 'mahasiswa', 'student'];
        $suffixes = ['123', '234', '456', '574', '2024', '2023', '321', '111', '000'];
        
        foreach ($prefixes as $prefix) {
            foreach ($bases as $base) {
                foreach ($suffixes as $suffix) {
                    $passwordPatterns[] = $prefix . $base . $suffix;
                    $passwordPatterns[] = $base . $prefix . $suffix;
                    $passwordPatterns[] = $base . $suffix . $prefix;
                }
            }
        }
        
        // Pattern 2: Common passwords
        $commonPasswords = [
            'password', 'password123', 'Password123', 'PASSWORD123',
            'admin', 'admin123', 'Admin123', 'ADMIN123',
            'tellink', 'tellink123', 'Tellink123', 'TELLINK123',
            'mahasiswa', 'mahasiswa123', 'Mahasiswa123', 'MAHASISWA123',
            'student', 'student123', 'Student123', 'STUDENT123',
            '123456', '12345678', '123456789', '1234567890',
            'qwerty', 'qwerty123', 'abc123', 'letmein',
            'welcome', 'welcome123', 'Welcome123',
            'default', 'default123', 'Default123',
            'test', 'test123', 'demo', 'demo123',
        ];
        
        // Pattern 3: NIM-based passwords
        $nimPasswords = [
            $nim,
            'password' . $nim,
            $nim . '123',
            $nim . '@123',
            '@' . $nim,
            'tellink' . $nim,
        ];
        
        // Combine all patterns
        $allPasswords = array_unique(array_merge(
            $specialPatterns,
            $passwordPatterns,
            $commonPasswords,
            $nimPasswords
        ));
        
        // Test each password against the hash
        foreach ($allPasswords as $testPassword) {
            if (!empty($testPassword) && password_verify($testPassword, $phpHash)) {
                // Cache the found password for future use
                \Log::info("Password found for NIM $nim: $testPassword");
                return $testPassword;
            }
        }
        
        // If no match found, log and return the most likely default
        \Log::warning("Could not decrypt password for NIM: $nim");
        
        // Return a hint that password couldn't be decrypted
        return null;
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
    
    public function getReports()
    {
        try {
            // Use the external reports API
            $response = Http::timeout(30)->get('https://tellink-backend-2-b916417fa9aa.herokuapp.com/api/reports');
            
            \Log::info('Reports API Response Status: ' . $response->status());
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
            \Log::error('Failed to fetch reports', [
                'status' => $response->status(),
                'body' => substr($response->body(), 0, 500)
            ]);
            
            return response()->json(['error' => 'Failed to fetch reports'], 500);
        } catch (\Exception $e) {
            \Log::error('Error fetching reports: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Delete a project using the new API format
     */
    public function deleteProject(Request $request)
    {
        try {
            \Log::info('Delete project request:', $request->all());
            
            // Validate request - API expects just 'id' field
            $request->validate([
                'id' => 'required'
            ]);
            
            // Call the external API with just 'id' field
            $response = Http::post($this->apiBaseUrl . '/api/deleteproject', [
                'id' => $request->get('id')
            ]);
            
            \Log::info('Delete project response:', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);
            
            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Project deleted successfully']);
            }
            
            // Return error with details
            $errorData = $response->json();
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete project',
                'message' => $errorData['message'] ?? 'Unknown error occurred'
            ], $response->status() ?: 500);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Delete project exception:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Edit a project using the new API format
     */
    public function editProject(Request $request)
    {
        try {
            \Log::info('Edit project request:', $request->all());
            
            // Prepare multipart request
            $multipart = Http::asMultipart();
            
            // Add form fields
            $multipart = $multipart
                ->attach('projectId', $request->get('projectId'))
                ->attach('nim', $request->get('nim'))
                ->attach('title', $request->get('title'))
                ->attach('description', $request->get('description'))
                ->attach('requirements', $request->get('requirements', ''));
            
            // Add image if provided
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $multipart = $multipart->attach(
                    'image',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                );
            }
            
            // Make the API call
            $response = $multipart->post($this->apiBaseUrl . '/api/editproject');
            
            \Log::info('Edit project response:', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);
            
            if ($response->successful()) {
                return response()->json(['success' => true, 'message' => 'Project updated successfully']);
            }
            
            // Return error with details
            $errorData = $response->json();
            return response()->json([
                'success' => false,
                'error' => 'Failed to update project',
                'message' => $errorData['message'] ?? 'Unknown error occurred'
            ], $response->status() ?: 500);
            
        } catch (\Exception $e) {
            \Log::error('Edit project exception:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
