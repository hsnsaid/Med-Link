<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\DoctorsFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Http\Resources\V1\DoctorCollection;
use App\Http\Resources\V1\DoctorResource;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter=new DoctorsFilter();
        $filterItems=$filter->transform($request);
        $filterItems+=["approved"=>true];
        $doctors=Doctor::where($filterItems);
        $doctors = $doctors->paginate()->appends($request->query());
        $doctors->getCollection()->transform(function ($doctor) {
            $doctor->picture = $doctor->picture ? asset("storage/" . $doctor->picture) : null;
            return $doctor;
        });    
        return new DoctorCollection($doctors);
    }
        public function doctors(Request $request)
    {
        $filter=new DoctorsFilter();
        $filterItems=$filter->transform($request);
        $doctors=Doctor::where($filterItems);
        $doctors = $doctors->paginate()->appends($request->query());
        $doctors->getCollection()->transform(function ($doctor) {
            $doctor->picture = $doctor->picture ? asset("storage/" . $doctor->picture) : null;
            return $doctor;
        });    
        return new DoctorCollection($doctors);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDoctorRequest $request)
    {
        $data=$request->validated();
        if ($request->hasFile('picture')) {
            $data['picture']=$request->file('picture')->store(options:'public');
        }
        $doctor=Doctor::create($data);
        $token=$doctor->createToken('doctor')->plainTextToken;
        $response=[
            'doctor'=>new DoctorResource($doctor),
            'token'=>$token
        ];
        return Response($response,201);
    }
    public function check(Request $request)
    {
        $fields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string']
        ]);    
        $doctor = Doctor::where('email', $fields['email'])->first();    
        if (!$doctor || !Hash::check($fields['password'], $doctor->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
        $token = $doctor->createToken('doctor')->plainTextToken;    
        $doctor->picture = $doctor->picture ? asset("storage/" . $doctor->picture) : null;
        return response()->json([
            'doctor' => new DoctorResource($doctor),
            'token' => $token
        ], 201);
    }    
    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        $doctor->picture = $doctor->picture ? asset("storage/" . $doctor->picture) : null;
        return new DoctorResource($doctor);
    }
    public function showAuthenticatedDoctor(Request $request)
    {
        $doctor=$request->user();
        $doctor->picture = $doctor->picture ? asset("storage/" . $doctor->picture) : null;
        return new DoctorResource($doctor);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        $update=false;
        $data=$request->validated();
        if($request->hasAny('soft')){
            $doctor->fill($data)->save();
            $update=true;
        }
        else{
            if(Hash::check($data['password'],$doctor->password)){
                if ($request->hasFile('picture')) {
                    $data['picture']=$request->file('picture')->store(options:'public');
                }    
                $doctor->fill($data)->save();
                $update=true;
            }
        }  
        return response()->json([
            'doctor' => new DoctorResource($doctor),
            'update'=>$update
        ], 201);
    }
    public function updatePassword(Request $request, Doctor $doctor){
        $data = $request->validate([
            'old_password'=>['required'],
            'password'=>['required','confirmed',Password::min(6)->numbers()]
        ]);
        if(Hash::check($data['old_password'],$doctor->password)){
            $doctor->update(['password'=>$data['password']]);
            return response()->json([
                'doctor'=>new DoctorResource($doctor),
                'update'=>true
            ]);    
        }
        return response()->json([
            'update'=>false
        ]);
    }
    public function stats()
    {
        $online=Doctor::where("status","online")->get()->count();
        $offline=Doctor::where("status","offline")->get()->count();
        $all=Doctor::all()->count();
        return response()->json([
            "totalDoctor"=>$all,
            "totalDoctorOnline"=>$online,
            "totalDoctorOffline"=>$offline
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return response('doctor has been deleted');
    }
    public function logout(Request $request)
    {
        $doctor = $request->user(); 
        if (!$doctor) {
            return response(['error' => 'User not authenticated'], 401);
        }
        $doctor->tokens()->delete();
        return response(['message' => 'Logged out successfully'], 200);
    }
}
