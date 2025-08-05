<?php

namespace App\Http\Controllers;

use App\Models\UserPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPostController extends Controller
{

    public function index()
    {
        $posts = UserPost::with('user')->latest()->get();
        return view('UserPost', compact('posts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string',
            'type' => 'in:text,image,file'
        ]);

        $post = UserPost::create([
            'user_id' => Auth::id(),
            'content' => $data['content'],
            'type' => $data['type'] ?? 'text'
        ]);
        
        return redirect()->back()->with('success', 'Post berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'content' => 'required|string'
        ]);

        $post = UserPost::findOrFail($id);
        
        // Check if user owns the post
        if ($post->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengedit post ini!');
        }
        
        $post->update(['content' => $data['content']]);
        
        return redirect()->back()->with('success', 'Post berhasil diupdate!');
    }

    public function destroy($id)
    {
        $post = UserPost::findOrFail($id);
        
        // Check if user owns the post
        if ($post->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus post ini!');
        }
        
        $post->delete();
        
        return redirect()->back()->with('success', 'Post berhasil dihapus!');
    }
}
