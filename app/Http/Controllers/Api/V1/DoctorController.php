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
        $data['picture']=$request->file('picture')->store(options:'public');
        $doctor=Doctor::create($data);
        // $doctor->tokens()->delete();
        // $token=$doctor->createToken('doctor')->plainTextToken;
        $response=[
            'doctor'=>new DoctorResource($doctor),
            // 'token'=>$token
        ];
        return Response($response,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        $doctor['picture']=asset("storage/$doctor->picture");
        return new DoctorResource($doctor);
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
}
