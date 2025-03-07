<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\V1\UserCollection;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new UserCollection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user=User::create([
            'name'=>$request['name'],
            'email'=>$request['email'],
            'password'=>Hash::make($request['password']),
            'phone_number'=>$request['phoneNumber'],
        ]);
        $user->tokens()->delete();
        $token=$user->createToken('user')->plainTextToken;
        $response=[
            'user'=>$user,
            'token'=>$token
        ];
        return Response($response,201);
    }
    public function check(Request $request){
        $feilds=$request->validate([
            'email'=>['required','email'],
            'password'=>['required','string'],
        ]);
        if (!Auth::attempt(['email' => $feilds['email'], 'password' => $feilds['password']])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }    
        $user = Auth::user();
        $user->tokens()->delete();    
        $token = $user->createToken('user')->plainTextToken;
    
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ],
            'token' => $token
        ], 200);
    }
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }
    public function showAuthenticatedUser(Request $request)
    {
        return new UserResource($request->user());
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        return 'yeah';
        // $user->update(['name]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
    }
    public function logout(Request $request)
    {
        $user = $request->user(); 
        if (!$user) {
            return response(['error' => 'User not authenticated'], 401);
        }
        $user->tokens()->delete();
        return response(['message' => 'Logged out successfully'], 200);
    }    
}