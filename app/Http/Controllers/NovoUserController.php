<?php

namespace App\Http\Controllers;

use App\User;
use Caffeinated\Shinobi\Models\Role;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NovoUserController extends Controller
{
    public function getAppData(Request $request)
    {
        $data = [
            "id"    => "Camtool",
            "name"  => config('app.url'),
            "roles" => Role::pluck('name'),
        ];

        return response()->json($data, 200, [], JSON_UNESCAPED_SLASHES);
    }

    public function createUser(Request $request)
    {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|string',
            'user_metadata.first_name' => 'required|string|max:255',
            'user_metadata.last_name' => 'required|string|max:255',
            'user_metadata.profile' => 'nullable|string',
        ]);

        // Return validation errors if they exist
        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors(),
            ], 422);
        }

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Check if the user exists by email
            $user = User::where('email', $request->input('email'))->first();

            if ($user) {
                // User exists, update the user information
                $user->nikname = $request->input('username');
                $user->name = $request->input('user_metadata.first_name') . ' ' .  $request->input('user_metadata.last_name');
                $user->password = bin2hex(random_bytes(12));
                $user->save();

                // Update the user's role
                $role = Role::where('name', $request->input('role'))->first();
                if ($role) {
                    $user->assignRole($role);
                }

                DB::commit();

                return response()->json([
                    'message' => 'User information updated successfully',
                    'user' => $user,
                ], 200);

            } else {
                // User does not exist, create a new one
                $newUser = new User();
                $newUser->nickname = $request->input('username');
                $newUser->email = $request->input('email');
                $newUser->name = $request->input('user_metadata.first_name') . ' ' . $request->input('user_metadata.last_name');
                $newUser->password = bin2hex(random_bytes(12));
                $newUser->save();

                // Assign the user role
                $role = Role::where('name', $request->input('role'))->first();
                if ($role) {
                    $newUser->assignRole($role);
                }

                DB::commit();

                return response()->json([
                    'message' => 'User created successfully',
                    'user' => $newUser,
                ], 201);
            }
        } catch (\Exception $e) {
            // Rollback if something goes wrong
            DB::rollBack();

            return response()->json([
                'error' => 'An error occurred',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteUser(Request $request)
    {
        /// Validate that the email is provided in the request
        // Manually validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors(),
            ], 422);
        }

        try {
            // Find the user by email
            $user = User::where('email', $request->get('email'))->first();

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            // Find the 'INACTIVO' role
            $inactiveRole = Role::where('name', 'INACTIVO')->first();

            if (!$inactiveRole) {
                return response()->json(['error' => 'Inactive role not found'], 404);
            }

            // Start a transaction to ensure data consistency
            DB::beginTransaction();

            // Assign the inactive role to the user
            $user->assignRoles($inactiveRole);

            DB::commit();

            return response()->json(['message' => 'User deactivated successfully'], 200);
        } catch (\Exception $e) {
            // Rollback in case of any error
            DB::rollBack();

            return response()->json([
                'error'   => 'An error occurred while deactivating the user',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function getLogin()
    {
        return view("auth0.login");
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        $url = config('novo_users.url');

        $data = [
            'user'     => $credentials['email'],
            'password' => $credentials['password'],
        ];

        $client = new Client();

        try {
            $response = $client->post($url, [
                'json'    => $data, // Esto envía los datos como JSON
                'headers' => [
                    'Content-Type' => 'application/json', // Especifica que es JSON
                ],
            ]);

            $userInfo = json_decode($response->getBody()->getContents(), true)['user_info'];

            $userEmail = $userInfo['email'];

            $user = User::where('email', $userEmail)->first();

            if ($user && !$user->hasRole('INACTIVO')) {
                Auth::login($user, true);
                return view("admin.dashboard");
            } else {
                return view("auth0.login")->with("errorMsg", "usuario no valido");
            }

        } catch (\Exception $e) {
            return view("auth.login")->with("errorMsg", "credenciales incorrectas");
        }
    }
}
