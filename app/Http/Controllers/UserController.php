<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->with('roles');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('id', $request->role));
        }

        if ($request->filled('status')) {
            if ($request->status === 'with_roles') {
                $query->has('roles');
            } elseif ($request->status === 'without_roles') {
                $query->doesntHave('roles');
            }
        }

        $users = $query->orderBy('name')->paginate(10)->withQueryString();

        $tableModelHasRoles = config('permission.table_names.model_has_roles', 'model_has_roles');
        $tableRoles = config('permission.table_names.roles', 'roles');

        $totalUsers = User::count();
        $usersWithRoles = DB::table($tableModelHasRoles)
            ->where('model_type', User::class)
            ->distinct('model_id')
            ->count('model_id');
        $usersWithoutRoles = max(0, $totalUsers - $usersWithRoles);
        $totalRoles = DB::table($tableRoles)->count();
        $allRoles = Role::orderBy('name')->get();

        return view('users.index', compact(
            'users',
            'totalUsers',
            'usersWithRoles',
            'usersWithoutRoles',
            'totalRoles',
            'allRoles'
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
