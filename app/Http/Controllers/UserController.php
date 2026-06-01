<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->with('roles')
            ->orderBy('name')
            ->paginate(10);

        $tableModelHasRoles = config('permission.table_names.model_has_roles', 'model_has_roles');
        $tableRoles = config('permission.table_names.roles', 'roles');

        $totalUsers = User::count();
        $usersWithRoles = DB::table($tableModelHasRoles)
            ->where('model_type', User::class)
            ->distinct('model_id')
            ->count('model_id');
        $usersWithoutRoles = max(0, $totalUsers - $usersWithRoles);
        $totalRoles = DB::table($tableRoles)->count();

        return view('users.index', compact(
            'users',
            'totalUsers',
            'usersWithRoles',
            'usersWithoutRoles',
            'totalRoles'
        ));
    }

    public function create()
    {
        $roles = Role::withCount('permissions')->orderBy('name')->get();

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'unique:users,email'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'roles'         => ['nullable', 'array'],
            'roles.*'       => ['integer', 'exists:roles,id'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->syncRoles($request->input('roles', []));

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $user = User::with('roles.permissions')->findOrFail($id);

        return view('users.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user  = User::with('roles')->findOrFail($id);
        $roles = Role::withCount('permissions')->orderBy('name')->get();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'unique:users,email,' . $id],
            'password'      => ['nullable', 'string', 'min:8', 'confirmed'],
            'roles'         => ['nullable', 'array'],
            'roles.*'       => ['integer', 'exists:roles,id'],
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles($request->input('roles', []));

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
