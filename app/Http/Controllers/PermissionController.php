<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view permissions', only: ['index', 'show']),
            new Middleware('permission:create permissions', only: ['create', 'store']),
            new Middleware('permission:edit permissions', only: ['edit', 'update']),
            new Middleware('permission:delete permissions', only: ['destroy']),
        ];
    }
    public function index(Request $request)
    {
        $query = Permission::query()->withCount('roles');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('guard')) {
            $query->where('guard_name', $request->guard);
        }

        if ($request->filled('status')) {
            if ($request->status === 'used') {
                $query->has('roles');
            } elseif ($request->status === 'unused') {
                $query->doesntHave('roles');
            }
        }

        $permissions = $query->orderBy('name')->paginate(10)->withQueryString();

        $tableRoleHasPermissions = config('permission.table_names.role_has_permissions', 'role_has_permissions');

        $totalPermissions = Permission::count();
        $totalRoles = Role::count();
        $permissionsAssignedToRoles = DB::table($tableRoleHasPermissions)
            ->distinct('permission_id')
            ->count('permission_id');
        $permissionsUnassigned = max(0, $totalPermissions - $permissionsAssignedToRoles);
        $totalAssignments = DB::table($tableRoleHasPermissions)->count();
        $guards = Permission::distinct()->pluck('guard_name');

        return view('permissions.index', compact(
            'permissions',
            'totalPermissions',
            'totalRoles',
            'permissionsAssignedToRoles',
            'permissionsUnassigned',
            'totalAssignments',
            'guards'
        ));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:125', 'unique:permissions,name'],
        ]);

        Permission::create([
            'name'       => $request->name,
            'guard_name' => 'web',
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $permission = Permission::with('roles')->findOrFail($id);

        return view('permissions.show', compact('permission'));
    }

    public function edit(string $id)
    {
        $permission = Permission::with('roles')->findOrFail($id);

        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, string $id)
    {
        $permission = Permission::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:125', 'unique:permissions,name,' . $id],
        ]);

        $permission->update(['name' => $request->name]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission berhasil dihapus.');
    }
}
