<?php

namespace App\Http\Controllers;

use App\Client as AppClient;
use App\User;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\App;
use Tymon\JWTAuth\Facades\JWTAuth;

class Auth0EndPoints extends Controller
{
    public function getAppData(Request $request){
        $id_token = 'base64:NYGgFCwPKR6FbWYN8IlUDhxDqIKPIkYjLVKOxyeARL4=';
        $appName  = 'camtool';
        $roles    = Role::orderBy('id','ASC')->pluck('name');
        return response()->json([
            'id' => $id_token,
            'name' => $appName,
            'roles' => $roles
        ]);
    }

    public function createUser(Request $request){
        request()->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required'
        ]);
        $data = request();
        $pw = bcrypt('passwordnovonordisk2021');
        $user = new User;
        $first_name = $data['user_metadata']['first_name'];
        $last_name  = $data['user_metadata']['last_name'];
        $user->name = $first_name . ' ' . $last_name;
        $user->email = $data['email'];
        $user->password = $pw;
        $user->nickname	= $data['username'];
        $email = $data['email'];
        if ($user->save()) {
            $userP = User::where('email',$email)->get('id');
            $userRol = User::findOrFail($userP[0]->id);
            $userRol->roles()->sync(request()->get('role'));
            // User::sendWelcomeEmail($user);
            // redirect()->route('user.index', compact('user'))
            return 'Guardado con éxito';
        }
    }

    public function deleteUser(Request $request){
        // dd(request()->username);
        if (request()->username) {
            $user = User::where('nickname', request()->username)->first();
            if (isset($user)) {
                $user->userStatus = 0;
                $user->update();
            }else{
                return 'El usuario no existe';
            }
            return 'Usuario inhabilitado con éxito';
        }
    }
}
