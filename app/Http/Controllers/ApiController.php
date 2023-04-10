<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{

    public function test(Request $request)
    {
        // Return a success response with the new user's details
        return response()->json([
            'message' => 'API is working!',
        ], 200);
    }

    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // Validate the request data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date_format:Y-m-d',
            'avatar' => 'nullable|image',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $filename = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('public/avatars', $filename);
        }

        // Create a new user in the database
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'avatar' => $filename,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Return a success response with the new user's details
        return response()->json([
            'message' => 'User registration successful',
            'user' => $user
        ], 201);
    }

    /**
     * Authenticate a user and generate a new access token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        // Authenticate the user credentials
        if (!Auth::attempt($request->only('email', 'password'), $request->remember_me)) {
            return response()->json([
                'message' => 'Invalid login credentials'
            ], 401);
        }

        // Generate a new access token
        $user = $request->user();
        $token = $user->createToken('access_token')->plainTextToken;
        $user->token = $token;
        $user->save();

        // Return a success response with the user's details and access token
        return response()->json([
            'message' => 'User authentication successful',
            'user' => $user,
            'access_token' => $token
        ], 200);
    }


    /**
     * Retrieve a user's details by ID or email.
     *
     * @param  int|string  $user_id
     * @return \Illuminate\Http\Response
     */
    public function viewProfile(Request $request)
    {
        // Retrieve the user by ID or email
        $user = User::where('token', $request->access_token)->first();

        // Return a success response with the user's details
        return response()->json([
            'message' => 'User details retrieved',
            'user' => $user
        ], 200);
    }


    /**
     * Update a user's details by ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|string  $userId
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request, $userId)
    {
        $user = User::find($userId);

        // Validate the request data
        $request->validate([
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'avatar' => 'nullable|image',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date_format:Y-m-d',
        ]);

        $filename = $user->avatar;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('public/avatars', $filename);
        }

        $user->fill(
            array_merge(
                Arr::except($request->all(), ['id', 'email', 'avatar']),
                array('avatar' => $filename)
            )
        );
        $user->save();

        return response()->json([
            'message' => 'User details updated',
            'response' => json_encode($request->all()),
            'user' => $user
        ], 200);
    }
}
