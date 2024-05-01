<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Spatie\Permission\Models\Role as Roles;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use DataTables;
use Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('users.user', compact('roles'));
    }

    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        $user = $this->userRepository->all();
        return Datatables::of($user)
            ->addIndexColumn()
            ->addColumn('role', function ($row) {
                return User::find($row->id)->roles[0]->name;
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                if ($row->id != 1) {
                    if (auth()->user()->can('user-edit')) {
                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editRoleBtn">Edit</a>';
                    }
                    if (auth()->user()->can('user-delete')) {
                        $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteRoleBtn">Delete</a>';
                    }
                    return $btn;
                }
            })
            ->rawColumns(['action'])
            ->make(true);
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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'roles' => 'required',
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = $this->userRepository->create($input);
        $user->assignRole((int)$request->input('roles'));
        return response()->json(['success', $input]);
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
        $user = $this->userRepository->find($id);
        $userRole = User::find($id)->roles[0]->id;
        return response()->json([
            'user' => $user,
            'userRole' => $userRole,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id.'id',
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }

        $user = $this->userRepository->update($input, $id);
        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole((int)$request->input('roles'));
        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->userRepository->delete($id);
        return response()->json(['success' => true]);
    }
}
