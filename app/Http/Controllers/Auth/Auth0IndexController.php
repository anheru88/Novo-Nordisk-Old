<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class Auth0IndexController extends Controller
{
    public function login()
    {
        $authorize_params = [
            'scope' => 'openid profile email',
            // Use the key below to get an access token for your API.
            // 'audience' => config('laravel-auth0.api_identifier'),
        ];
        return App::make('auth0')->login(null, null, $authorize_params);
    }

    public function logout()
    {
        Auth::logout();
        $logoutUrl = sprintf(
            'https://%s/v2/logout?client_id=%s&returnTo=%s',
            env('AUTH0_DOMAIN'),
            env('AUTH0_CLIENT_ID'),
            env('APP_URL'));
        return  Redirect::intended($logoutUrl);
    }
}
