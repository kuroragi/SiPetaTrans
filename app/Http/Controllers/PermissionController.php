<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::query()
            ->withCount('roles')
            ->orderBy('name')
            ->paginate(10);

        $tableRoleHasPermissions = config('permission.table_names.role_has_permissions', 'role_has_permissions');

        $totalPermissions = Permission::count();
        $totalRoles = Role::count();
        $permissionsAssignedToRoles = DB::table($tableRoleHasPermissions)
            ->distinct('permission_id')
            ->count('permission_id');
        $permissionsUnassigned = max(0, $totalPermissions - $permissionsAssignedToRoles);
        $totalAssignments = DB::table($tableRoleHasPermissions)->count();

        return view('permissions.index', compact(
            'permissions',
            'totalPermissions',
            'totalRoles',
            'permissionsAssignedToRoles',
            'permissionsUnassigned',
            'totalAssignments'
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
