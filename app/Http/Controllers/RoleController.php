<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Spatie\Permission\Models\Role as Roles;
use Spatie\Permission\Models\Permission as Permissions;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;
use DataTables;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the role.
     */
    public function index()
    {
        $permission = Permission::get();
        return view('roles.roles', compact('permission'));
    }

    /**
     * Fetch a listing of the role.
     */    public function list()
    {
        $role = $this->roleRepository->all();
        return Datatables::of($role)
            ->addIndexColumn()
            ->addColumn('permission', function ($row) {
                $permissionrole = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
                    ->where("role_has_permissions.role_id", $row->id)
                    ->get();
                $permissionrole = $permissionrole->map(function ($item) {
                    return $item->name;
                })->toArray();
                return $permissionrole;
            })
            ->addColumn('action', function ($row) {
                if ($row->id != 1) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editRoleBtn">Edit</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteRoleBtn">Delete</a>';
                    return $btn;
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required|',
        ]);

        $role = Roles::create(['name' => $request->input('name')]);
        $permission = [];
        foreach ($request->input('permission') as $value) {
            $permission[$value] = (int)$value;
        }
        $role->syncPermissions($permission);
        return response()->json(['success' => true]);
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role)
    {

        $roles = $this->roleRepository->find($role->id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $role->id)
            ->get();
        return response()->json([
            'role' => $roles,
            'permissions' => $rolePermissions,
        ]);
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name,' . $role->id,
            'permission' => 'required',
        ]);

        $this->roleRepository->update(['name' => $request->input('name')], $role->id);
        $permission = [];
        foreach ($request->input('permission') as $value) {
            $permission[$value] = (int)$value;
        }
        $role->syncPermissions($permission);
        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        if ($role->id !== 1) {
            $this->roleRepository->delete($role->id);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Cannot delete the default role.']);
    }
}
