<?php

namespace App\Http\Controllers;

use App\Client;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\DiscountLevels;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Filesystem\Filesystem;


class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $isEditor = auth()->user()->hasPermissionTo('users.index');
        if ($isEditor) {
            $users = User::orderBy('name', 'ASC')
                ->with('roles')->get();
            //dd($users);
            return view('admin.users.index', compact('users'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $isEditor = auth()->user()->hasPermissionTo('users.create');
        if ($isEditor) {
            $roles = Role::get();
            $discLevels = DiscountLevels::all();
            return view('admin.users.create', compact('roles', 'discLevels'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        auth()->user()->hasPermissionTo('users.create');

        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6|confirmed',
            //'firm'      => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
            'cargo'     => 'required|string'
        ]);
        $hasFile = $request->hasFile('firm');

        $data            = $request;
        $uuid_firm       = $request->all();
        $uuid            = $uuid_firm['uuid_firm'] = (string) Str::uuid();

        $user            = new User;
        $user->name      = strtoupper($data['name']);
        $user->email     = strtoupper($data['email']);
        $user->nickname  = strtoupper($data['nickname']);
        $user->phone     = $data['phone'];
        $user->address   = strtoupper($data['address']);
        $user->position  = strtoupper($data['position']);
        $user->uuid_firm = $uuid;
        $user->password  = bcrypt($data['password']);

        if ($hasFile) {
            $namefile        = $request->file('firm')->getClientOriginalName();
            $user->firm      = $request->file('firm')->storeAs('public', $namefile);
        }

        if ($data['auth'] == 1) {
            $user->is_authorizer = 1;
            $user->authlevel     = $data['id_level_discount'];
        }

        if ($user->save()) {
            $user->roles()->sync($request->get('roles'));
            return redirect()->route('users.index')->withSuccess('Usuario registrado correctamente');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $isEditor = auth()->user()->hasPermissionTo('users.show');
        if ($isEditor) {
            return view('admin.users.show', compact('user'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $isEditor     = auth()->user()->hasPermissionTo('users.edit');
        if ($isEditor) {
            $roles    = Role::get();
            $levels   = DiscountLevels::get();
            $userFirm = User::where('id', $id)->pluck('firm');
            $firmJson = json_decode($userFirm,true);
            // dd($firmJson);
            $user     = User::find($id);
            // dd($user->is_authorizer);
            if ($user->is_authorizer == 2) {
                $user->discLevel;
            }
            return view('admin.users.edit', compact('user', 'roles', 'levels', 'firmJson'));
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $isEditor = auth()->user()->hasPermissionTo('users.edit');
        if ($isEditor) {
            // actualizacion de usuario
            $user                   = User::find($id);
            $user->name             = strtoupper($request['name']);
            $user->email            = strtoupper($request['email']);
            $user->nickname         = strtoupper($request['nickname']);
            $user->phone            = $request['phone'];
            $user->address          = strtoupper($request['address']);
            $user->position         = strtoupper($request['position']);
            $roles                  = $request['roles'];
            $clave                  = array_search(2, $roles); // $clave = 2;
            $user->is_authorizer    = $request['is_authorizer'];
            $user->authlevel        = $request->authlevel;
            if($user->update()){
                if ($request->file('firm')) {
                    $path = public_path() . '/uploads/firms/';
                    File::makeDirectory($path, $mode = 0777, true, true);
                    $file = $request->file('firm');
                    $fileName = $request->nickname.time().'.'.$file->getClientOriginalExtension();
                    $user = User::find($id);
                    $user->firm = $fileName;
                    if ($user->save()) {
                        $file->move($path, $fileName);
                    }
                }
                //actualizacion de roles
                $user->roles()->sync($request->get('roles'));
                return redirect()->route('users.index')->with('success', 'Usuario modificado correctamente');
            }else{
                return redirect()->back()->withInput()->with('warning', 'Existio un error al crear el usuario');
            }
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $isEditor = auth()->user()->hasPermissionTo('users.destroy');
        if ($isEditor) {
            $user = User::find($id);
            $queryClient = Client::where('id_diab_contact', $id)->get();
            if (sizeof($queryClient) > 0) {
                return redirect()->back()->withError('El usuario ' . $user->name . ' no puede ser eliminado debido a que esta asignado a un cliente');
            } else {

                $user->delete();
                return redirect()->back()->withSuccess('Usuario eliminado correctamente');
            }
        } else {
            abort(403, 'Acción no autorizada.');
        }
    }


    public function getUsers(Request $request)
    {
        //$users = User::all();
        $id_user = $request->idUser;
        $users = User::getUsers($id_user);
        return $users;
    }
}
