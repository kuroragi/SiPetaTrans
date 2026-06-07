<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view roles', only: ['index', 'show']),
            new Middleware('permission:create roles', only: ['create', 'store']),
            new Middleware('permission:edit roles', only: ['edit', 'update']),
            new Middleware('permission:delete roles', only: ['destroy']),
        ];
    }
    public function index(Request $request)
    {
        $query = Role::query()->with('permissions')->withCount('permissions');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            if ($request->status === 'with_permissions') {
                $query->has('permissions');
            } elseif ($request->status === 'without_permissions') {
                $query->doesntHave('permissions');
            }
        }

        $roles = $query->orderBy('name')->paginate(10)->withQueryString();

        $tableRoleHasPermissions = config('permission.table_names.role_has_permissions', 'role_has_permissions');

        $totalRoles = Role::count();
        $rolesWithPermissions = Role::has('permissions')->count();
        $rolesWithoutPermissions = max(0, $totalRoles - $rolesWithPermissions);
        $totalPermissions = Permission::count();
        $totalRolePermissionLinks = DB::table($tableRoleHasPermissions)->count();

        return view('roles.index', compact(
            'roles',
            'totalRoles',
            'rolesWithPermissions',
            'rolesWithoutPermissions',
            'totalPermissions',
            'totalRolePermissionLinks'
        ));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get();

        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:125', 'unique:roles,name'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role = Role::create([
            'name'       => $request->name,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($request->input('permissions', []));

        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $role = Role::with(['permissions', 'users'])->findOrFail($id);

        return view('roles.show', compact('role'));
    }

    public function edit(string $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::orderBy('name')->get();

        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name'          => ['required', 'string', 'max:125', 'unique:roles,name,' . $id],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->input('permissions', []));

        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil dihapus.');
    }
}
