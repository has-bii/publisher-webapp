<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Laravel\Fortify\Rules\Password;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        try {
            // TODO: Validate request
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // TODO: Find user by email
            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error('Unauthorized', 401);
            }

            $user = User::where('email', $request->email)->first();
            if (!Hash::check($request->password, $user->password)) {
                throw new Exception('Invalid password');
            }

            // TODO: Generate token
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            // TODO: Return response
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'Login success');
        } catch (Exception $e) {
            return ResponseFormatter::error('Authentication failed');
        }
    }

    public function register(Request $request)
    {
        try {
            // TODO: Validate request
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', new Password],
            ]);

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            // Generate token
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            // Return response
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'User registered');
        } catch (Exception $e) {
            // TODO: Return error response
            return ResponseFormatter::error($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();

        return ResponseFormatter::success($token, 'Logout success');
    }

    public function fetch(Request $request)
    {

        $user = $request->user();

        return ResponseFormatter::success($user, 'Fetch Success');
    }

    public function update(Request $request)
    {
        try {
            $email = $request->input('email');

            // Get company
            $user = $request->user();

            // Check if company exists
            if (!$user) {
                throw new Exception('User not found');
            }

            // Update user
            $user->update([
                'name' => $request->name,
                'email' => isset($email) ? $email : $user->email,
            ]);

            return ResponseFormatter::success($user, 'User updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
