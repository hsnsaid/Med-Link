<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleAppointmentRequest;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\V1\AppointmentCollection;
use App\Http\Resources\V1\AppointmentResource;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

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
        $data=$request->validated();
        $appointment->fill($data)->save();
        $response=[
            "appointment"=>new AppointmentResource($appointment),
            "update"=>true
        ];
        return response($response,200);
    }
    public function scheduledAppointment(ScheduleAppointmentRequest $request, Appointment $appointment)
    {
        $data=$request->validated();
        $appointment->update(["user_id"=>$data['user_id']]);
        $response=[
            "appointment"=>new AppointmentResource($appointment),
            "update"=>true
        ];
        return response($response,200);
    }
    public function scheduledUser(Request $request)
    {
        $doctor = $request->user();
        $appointments = Appointment::with('user')
            ->where('doctor_id', $doctor->id)
            ->whereNotNull('user_id')
            ->groupBy('date')
            ->get();
        $result=[
                    'appointments' => $appointments->values()
                ];
        return response()->json($result, 200);
    }
        /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
