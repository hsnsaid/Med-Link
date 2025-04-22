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
use Illuminate\Validation\Rules\Password;

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
            "age"=> $request['age'],
            "sexe"=>$request['sexe'],
            "chronic_disease"=>$request['chronicDisease'],
            "groupage"=>$request['groupage'],
        ]);
        $user->tokens()->delete();
        $token=$user->createToken('user')->plainTextToken;
        $response=[
            'user'=>new UserResource($user),
            'token'=>$token
        ];
        return Response($response,201);
    }
    public function check(Request $request){
        $fields=$request->validate([
            'email'=>['required','email'],
            'password'=>['required','string'],
        ]);
        if (!Auth::attempt(['email' => $fields['email'], 'password' => $fields['password']])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }    
        $user = Auth::user();
        $token = $user->createToken('user',['appointment'])->plainTextToken;
    
        return response()->json([
            'user' => new UserResource($user),
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
        $update=false;        
        $data = $request->validated();
        if(Hash::check($data['password'],$user->password)){
            $user->fill($data)->save();
            $update=true;
        }
        return response()->json([
            'user'=>new UserResource($user),
            'update'=>$update
        ]);
    }
    public function updatePassword(Request $request, User $user){
        $update=false;        
        $data = $request->validate([
            'old_password'=>['required'],
            'password'=>['required','confirmed',Password::min(6)->numbers()]
        ]);
        if(Hash::check($data['old_password'],$user->password)){
            $user->update(['password'=>$data['password']]);
            $update=true;
        }
        return response()->json([
            'user'=>new UserResource($user),
            'update'=>$update
        ]);
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