<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
