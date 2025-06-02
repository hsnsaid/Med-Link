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

        return response()->json([
            "totalUser"=>$totalUser,
            "totalDoctor"=>$totalDoctor,
            "totalAppointment"=>$totalAppointment
        ],200);
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
    public function circle()
    {
        $counts = Doctor::all()->groupBy('speciality')->map(function ($group) {
            return $group->count();
        });
        return response()->json($counts);
    }
    public function diagram(){
        $kids=User::where('age','<',12)->count();
        $teen=User::whereBetween('age',[13, 21])->count();
        $adult=User::whereBetween('age',[22, 49])->count();
        $olders=User::whereBetween('age',[50, 99])->count();
        return response()->json([
            'kids' => $kids,
            'teen'=>$teen,
            'adult'=>$adult,
            'olders'=>$olders
        ]);
    }
    public function approve(Request $request){
        $request->validate([
            'DoctorId'=>['required','exists:doctors,id']
        ]);
        $doctor=Doctor::where('id',$request['DoctorId'])->first();
        if($doctor->approved==false){
            $doctor->approved=True;
            $doctor->save();
            return response()->json([
                'update' => true,
                'doctor'=>$doctor
            ]);
        }else{
            return response()->json([
                'update' => false,
                'doctor'=>$doctor,
                "message"=>'doctor already is approved'
            ]);
        }
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
