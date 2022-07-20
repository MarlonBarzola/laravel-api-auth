<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {

        $appointments = DB::table('appointments')
                        ->join('pets', 'appointments.pet_id', '=', 'pets.id')
                        ->where('pets.id', Auth::user()->id)
                        ->get();
        return response()->json(['appointments' =>  $appointments], 200);
    }

    public function create(Request $request) {
        //validate incoming request 
        $this->validate($request, [
            'pet_id' => 'required|numeric',
            'date' => 'required',
            'hour' => 'required'
        ]);

        try {

            $pet = Pet::findOrFail($request->input('pet_id'));
            if($pet->user_id != Auth::user()->id) {
                return response()->json(['message' => 'Unauthorized!'], 401);
            }

            $appointment = Appointment::create([
                'pet_id' => $request->input('pet_id'),
                'date' => $request->input('date'),
                'hour' => $request->input('hour'),
            ]);

            //return successful response
            return response()->json(['appointment' => $appointment, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Appointment Registration Failed!'], 409);
        }
    }

    public function show($id) {
        try {
            $appointment = Appointment::findOrFail($id);
            if($appointment->pet->user->id != Auth::user()->id) {
                return response()->json(['message' => 'Unauthorized!'], 401);
            }

            return response()->json(['appointment' => $appointment], 200);
            

        } catch (\Exception $e) {

            return response()->json(['message' => 'Appointment not found!'], 404);
        }
    }

    public function update(Request $request, $id) {

        //validate incoming request 
        $this->validate($request, [
            'date' => 'required',
            'hour' => 'required'
        ]);

        try {

            $appointment = Appointment::findOrFail($id);

            if($appointment->pet->user->id != Auth::user()->id) {
                return response()->json(['message' => 'Unauthorized!'], 401);
            }

            if($appointment->fill($request->all())->save()) {
                return response()->json(['appointment' => $appointment], 200);
            }

            return response()->json(['message' => 'Appointment Update Failed!'], 409);


        } catch (\Exception $e) {
            return $e;
            return response()->json(['message' => 'Appointment Update Failed!'], 409);
        }
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);

        if($appointment->pet->user->id != Auth::user()->id) {
            return response()->json(['message' => 'Unauthorized!'], 401);
        }

        if(Pet::destroy($appointment->id)) {
            return response()->json(['message' => 'Appointment Deleted!'], 200);
        }
        return response()->json(['message' => 'Appointment not found!'], 404);
    }

}
