<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\V1\AppointmentCollection;
use App\Http\Resources\V1\AppointmentResource;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new AppointmentCollection(Appointment::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request)
    {
        $data=$request->validated();
        foreach($data['time'] as $time){
            $appointment[]=Appointment::create([
                'doctor_id'=>$data['doctor_id'],
                'date'=>$data['date'],
                'time'=>$time
            ]);
        }
        $response=['appointment'=>new AppointmentCollection($appointment)];
        return Response($response,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        return new AppointmentResource($appointment);
    }
    public function showDoctorAppointment(int $id)
    {
        $appointment=Appointment::where('doctor_id',$id)
                                ->whereNotNull('user_id')
                                ->get();
        return new AppointmentCollection($appointment);
    }
    public function showScheduledAppointment(int $id)
    {
        $appointment=Appointment::where('doctor_id',$id)
                                ->whereNull('user_id')
                                ->get();
        return new AppointmentCollection($appointment);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
