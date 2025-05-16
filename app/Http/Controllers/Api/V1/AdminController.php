<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function stats(){
        $totalUser=User::all()->count();
        $totalDoctor=Doctor::all()->count();
        $totalAppointment=Appointment::all()->count();
        return response(["total user is "=>$totalUser,"totalDoctor"=>$totalDoctor,"totalAppointment"=>$totalAppointment]);
    }
    public function login(Request $request){
        $fields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string']
        ]);    
        $admin = Admin::where('email', $fields['email'])->first();   
        if (!$admin || !Hash::check($fields['password'], $admin->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
         $token = $admin->createToken('admin')->plainTextToken;    
        return response()->json([
            'admin' => $admin,
            'token' => $token
        ], 200);
    }
    public function logout(Request $request)
    {
        $admin = $request->user(); 
        if (!$admin) {
            return response(['error' => 'admin not authenticated'], 401);
        }
        $admin->tokens()->delete();
        return response(['message' => 'Logged out successfully'], 200);
    }

}
