<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function login()
    {

        $token = "";
        $this->validate(request(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        //dd(request()->email);

        $user = User::where('email', strtolower ( request()->email) )->first();

        if ($user) {
       // dd($user);
        Auth::login($user, true);
        return view("admin.dashboard");
        }else {
            return view("auth.login")->with("errorMsg", "credenciales incorrectas");
        }
        // Autenticacion Novo Auth0
        if ($user) {

            $nick = $user->nickname;
            $client = new Client();

            $AUTH0_SCOPE = 'openid profile email';
            $AUTH0_CONNECTION = 'NovoUsers';
            $AUDIENCE_URL = 'https://' . env('AUTH0_DOMAIN') . '/api/v2/';
            $OAUTH_TOKEN_URL = 'https://' . env('AUTH0_DOMAIN') . '/oauth/token';

            //dd(request()->password);

            try {
                $response = $client->post($OAUTH_TOKEN_URL, [
                    'json' => [
                        'client_id'     => env('AUTH0_CLIENT_ID'),
                        'client_secret' => env('AUTH0_CLIENT_SECRET'),
                        'grant_type'    => 'password',
                        'audience'      => $AUDIENCE_URL,
                        'username'      => strtolower(request()->email),  # Usernames in Auth0 are lowercase, in the apps they should be uppercase. desarrollos@hqr.com.co
                        'password'      => request()->password, # novonordiskcolombia2019
                        'connection'    => $AUTH0_CONNECTION,
                        'scope'         => $AUTH0_SCOPE
                    ]
                ]);
                $token = json_decode($response->getBody());
                $code = $response->getStatusCode();
                $token = $token->access_token;
                //dd($code);
                // dd(json_decode($response->getBody()->access_token));
            } catch (ClientException $exception) {
                $responseBody = $exception;
                $code = $responseBody->getResponse()->getStatusCode();
                // dd($responseBody->getResponse()->getStatusCode());
            }


            if ($code == 200 && $token != "") {
                Auth::login($user);
                return view("admin.dashboard");
            } else {
                return view("auth.login")->with("errorMsg", "credenciales incorrectas");
            }
        } else {
            return view("auth.login")->with("errorMsg", "credenciales incorrectas");
        }
    }
}
