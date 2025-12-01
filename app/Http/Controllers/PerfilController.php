<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public function show()
    {
        return view('perfil.show', ['user' => Auth::user()]);
    }

    public function edit()
    {
        return view('perfil.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|max:2048', // 2MB Max
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($user->foto) {
                // Remove 'storage/' prefix if present for Storage::delete
                $oldPath = str_replace('storage/', '', $user->foto);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('foto')->store('profile-photos', 'public');
            $user->foto = 'storage/' . $path;
        }

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('perfil.show')->with('success', 'Perfil actualizado correctamente.');
    }
}
