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

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
            $data['picture']=$request->file('picture')->store('public');
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
        $doctor['picture']=asset("storage/$doctor->picture");
        return new DoctorResource($doctor);
    }
    public function showAuthenticatedDoctor(Request $request)
    {
        return new DoctorResource($request->user());
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        //
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
