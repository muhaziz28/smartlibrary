<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index()
    {
        $perPage = 10;
        $currentPage = request('page', 1);

        $totalItems = Role::count();

        $totalPages = ceil($totalItems / $perPage);
        $role = Role::skip(($currentPage - 1) * $perPage)->take($perPage)->get();
        // return response()->json($role);

        return view('role.index', compact('role', 'totalItems', 'totalPages', 'currentPage', 'perPage'));
    }



    public function store(Request $request)
    {
        $role = new Role();
        $role->role_name = $request->role_name;
        $role->role_description = $request->role_description;
        $role->save();

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully!'
        ]);
    }

    public function show($id)
    {
        try {
            $role = Role::find($id);

            return response()->json([
                'success' => true,
                'data' => $role
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found!'
            ]);
        }
    }

    public function update(Request $request)
    {
        $role = Role::find($request->id);
        $role->role_name = $request->role_name;
        $role->role_description = $request->role_description;
        $role->save();

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully!'
        ]);
    }
}
