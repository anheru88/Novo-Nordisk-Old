<?php

namespace App\Http\Controllers\Auth;

use App\Client;
use App\Http\Controllers\Controller;
use App\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Session;
use Validator;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
     */

    use AuthenticatesUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
       // $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

//login

    protected function getLogin()
    {
        return view("auth.login");
    }


    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        dd($request('password'));

        $credentials = $request->only('email', 'password');

        $client = new Client();

        $AUTH0_SCOPE = ['openid', 'profile', 'email'];
        $AUTH0_CONNECTION = 'NovoUsers';
        $AUDIENCE_URL = 'https://' . env('AUTH0_DOMAIN') . '/api/v2/';
        $OAUTH_TOKEN_URL = 'https://' . env('AUTH0_DOMAIN') . '/oauth/token';
        
        try {
            $response = $client->post($OAUTH_TOKEN_URL,[
                'json' => [
                    'audience' => $AUDIENCE_URL,
                    'grant_type' => 'password',
                    'client_id' => env('AUTH0_KEY'),
                    'client_secret' => env('AUTH0_SECRET'),
                    'username' => 'desarrollos@hqr.com.co',  # Usernames in Auth0 are lowercase, in the apps they should be uppercase.
                    'password' => 'novonordiskcolombia2019',
                    'connection' => $AUTH0_CONNECTION,
                    'scope' => 'openid profile email',
                    ]
            ]);
            dd(json_decode($response->getBody()));
        } catch (ClientException $exception) {
            $responseBody = $exception;
            dd($responseBody->getResponse()->getStatusCode());
        }

        if ($this->auth->attempt($credentials, $request->has('remember'))) {
            return view("admin.dashboard");
        }else{
            return view("auth.login")->with("errorMsg", "credenciales incorrectas");
        }
    }

    public function postLoginAdmin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if ($this->auth->attempt($credentials, $request->has('remember'))) {
            return view("admin.dashboard");
        }else{
            return view("auth.login")->with("errorMsg", "credenciales incorrectas");
        }
    }

//login

    //registro

    protected function getRegister()
    {
        return view("auth.register");
    }

    protected function postRegister(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        $data = $request;

        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);

        

        if ($user->save()) {

            return "se ha registrado correctamente el usuario";

        }

    }

//registro

    protected function getLogout()
    {
        $this->auth->logout();

        Session::flush();

        return redirect('login');
    }

}
