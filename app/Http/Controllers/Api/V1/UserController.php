<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\ChatSessionStarted;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\V1\UserCollection;
use App\Http\Resources\V1\UserResource;
use App\Models\ChatSession;
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
        $user=User::where('email',$fields['email'])->first();
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }    
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
    public function updateBalance(Request $request){
        $user=$request->user();
        $validated=$request->validate([
            'CardNumber'=>['required',"min:10"],
            'ExpiryDate'=>['required'],
            'CVC'=>['required'],
            'package'=>['required','in:1,2,3,4'],
            'password'=>['required']
        ]);
        $packageAmounts=['1'=>100,'2'=>500,'3'=>1000,'4'=>2000];
        if(! Hash::check($validated['password'],$user->password)){
            return response()->json(['message' => 'Unauthorized to update the balance.'], 403);
        }
        $amountToAdd = $packageAmounts[$validated['package']];
        $user->balance+=$amountToAdd;
        $user->save();
        return response()->json(['message' => 'Balance updated successfully.', 'new_balance' => $user->balance], 200);       
    }
    /**
     * Remove the specified resource from storage.
     */
    public function balanceToChat(Request $request){
        $user=$request->user();
        $validated=$request->validate([
            'amount'=>['required','numeric'],
            'doctorID'=>['required','exists:doctors,id']
        ]);
        if($user->balance<$validated['amount']){
            return response()->json(['message' => "you don't have enough balance to do this action ."], 403);
        }
        $user->balance-=$validated['amount'];
        $user->save();       
        $chatSession=ChatSession::create([
            'user_id'=>$user->id,
            'doctor_id'=>$validated['doctorID'],
            'start_at'=>now(),
        ]);
        // $chatSession->messages()->create([
        //     'sender_id' => null,
        //     'sender_type' => 'System',
        //     'message' => "🟡 User {$user->name} has connected and is waiting for consultation...",
        // ]);
        event(new ChatSessionStarted($chatSession, $user));
        return response()->json(['message' => "Welcom to the chat",'session'=>$chatSession], 200);
    }
    public function destroy(User $user)
    {
        $user->delete();
        return response("user has been deleted");
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