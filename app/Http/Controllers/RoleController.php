<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::query()
            ->with('permissions')
            ->withCount('permissions')
            ->orderBy('name')
            ->paginate(10);

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
