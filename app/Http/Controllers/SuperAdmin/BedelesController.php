<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Career;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class BedelesController extends Controller
{
    public function index()
    {
        // Get users with role 'bedel'
        $bedeles = User::whereHas('role', function ($q) {
            $q->where('nombre', 'bedel');
        })->with('career')->paginate(10);

        return view('superadmin.bedeles.index', compact('bedeles'));
    }

    public function create()
    {
        $careers = Career::all();
        return view('superadmin.bedeles.create', compact('careers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'career_id' => ['required', 'exists:careers,id'],
        ]);

        $bedelRole = Role::where('nombre', 'bedel')->first();

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $bedelRole->id,
            'career_id' => $request->career_id,
        ]);

        return redirect()->route('superadmin.bedeles.index')->with('success', 'Bedel creado correctamente.');
    }

    public function edit($id)
    {
        $bedel = User::findOrFail($id);
        $careers = Career::all();
        return view('superadmin.bedeles.edit', compact('bedel', 'careers'));
    }

    public function update(Request $request, $id)
    {
        $bedel = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $bedel->id],
            'career_id' => ['required', 'exists:careers,id'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $bedel->name = $request->name;
        $bedel->email = $request->email;
        $bedel->career_id = $request->career_id;

        if ($request->filled('password')) {
            $bedel->password = Hash::make($request->password);
        }

        $bedel->save();

        return redirect()->route('superadmin.bedeles.index')->with('success', 'Bedel actualizado correctamente.');
    }

    public function destroy($id)
    {
        $bedel = User::findOrFail($id);
        $bedel->delete();
        return redirect()->route('superadmin.bedeles.index')->with('success', 'Bedel eliminado correctamente.');
    }
}
