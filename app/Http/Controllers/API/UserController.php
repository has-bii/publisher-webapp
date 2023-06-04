<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Laravel\Fortify\Rules\Password;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePhotoRequest;
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
                'password' => Hash::make($request->password),
                'photo' => 'profile_dummy.png',
                'role_id' => 3,
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

    public function fetch_users(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $email = $request->input('email');
        $role_id = $request->input('role_id');
        $order_by = $request->input('order_by', 'asc');
        $notUser = $request->input('not_user');
        $limit = $request->input('limit', 100);

        $users = User::with('role')->orderBy('name', $order_by);

        // Single Fetch
        if ($id) {
            $user = $users->with('publishers')->find($id);

            if (!$user) {
                return ResponseFormatter::error('Data not found!');
            }

            return ResponseFormatter::success($user, 'Fetch success');
        }

        if ($notUser) {
            $users = User::query()->whereNot('id', $notUser)->with('role')->orderBy('name', $order_by);
        }

        if ($name) {
            $users->where('name', 'like', '%' . $name . '%');
        }

        if ($email) {
            $users->where('name', 'like', '%' . $email . '%');
        }

        if ($role_id) {
            $role_id = explode(',', $role_id);

            $users->whereIn('role_id', $role_id);
        }

        return ResponseFormatter::success($users->paginate($limit), 'Fetch success');
    }

    public function update(Request $request)
    {
        try {
            $email = $request->input('email');
            $phone = $request->input('phone');

            $user = $request->user();

            if (!$user) {
                throw new Exception('User not found');
            }

            // Update user
            $user->update([
                'name' => $request->name,
                'email' => isset($email) ? $email : $user->email,
                'phone' => isset($phone) ? $phone : $user->phone,
            ]);

            return ResponseFormatter::success($user, 'User updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function change_photo(UpdatePhotoRequest $request, $id)
    {
        try {
            $photoFile = $request->file('photo');

            $fileName = $photoFile->getClientOriginalName();
            $publicPath = public_path('storage/photos/');

            $photoFile->move($publicPath, $fileName);

            $photo = $fileName;

            $user = User::find($id);

            if (!$user) {
                throw new Exception('User not found');
            }

            $user->update([
                'photo' => $photo
            ]);

            return ResponseFormatter::success($user, 'Photo changed');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage());
        }
    }

    public function update_user(Request $request, $id)
    {
        try {
            $role_id = $request->input('role_id');

            $user = User::find($id);

            if (!$user) {
                throw new Exception('User not found');
            }

            // Update user
            $user->update([
                'role_id' => $role_id,
            ]);

            return ResponseFormatter::success($user, 'User updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
