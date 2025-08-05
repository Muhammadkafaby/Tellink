<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return view('ListUser', compact('users'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }
    
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,'.$user->id,
            'password' => 'required|string'
        ]);
        
        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors(['password' => 'Password salah!'])->withInput();
        }
        
        // Update data if password is correct
        if ($request->filled('name')) {
            $user->name = $request->name;
        }
        
        if ($request->filled('email')) {
            $user->email = $request->email;
        }
        
        $user->save();
        
        return redirect()->back()->with('success', 'Profile berhasil diupdate!');
    }
    
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$id
        ]);

        $user = User::findOrFail($id);
        $user->update($data);
        
        return redirect()->back()->with('success', 'User berhasil diupdate!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect('/listuser')->with('success', 'User berhasil dihapus!');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = Auth::user();
        
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('uploads/avatars'), $filename);
            
            $user->avatar = 'uploads/avatars/' . $filename;
            $user->save();
            
            return redirect()->back()->with('success', 'Avatar berhasil diupload!');
        }
        
        return redirect()->back()->with('error', 'Gagal upload avatar!');
    }
}
