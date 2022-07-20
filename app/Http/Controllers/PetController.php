<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $pets = Pet::where('id', Auth::user()->id)->get();
        return response()->json(['pets' =>  $pets], 200);
    }

    public function create(Request $request) {
        //validate incoming request 
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        try {

            $pet = Pet::create([
                'user_id' => Auth::user()->id,
                'name' => $request->input('name'),
                'description' => $request->input('description')
            ]);

            //return successful response
            return response()->json(['pet' => $pet, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Pet Registration Failed!'], 409);
        }
    }

    public function show($id) {
        try {
            $pet = Pet::where([
                ['id', $id],
                ['user_id', Auth::user()->id]
            ])->first();

            if($pet) {
                return response()->json(['pet' => $pet], 200);
            }

            return response()->json(['message' => 'Pet not found!'], 404);

        } catch (\Exception $e) {

            return response()->json(['message' => 'Pet not found!'], 404);
        }
    }

    public function update(Request $request, $id) {

        //validate incoming request 
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        try {
            $pet = Pet::findOrFail($id);
            
            if($pet->fill($request->all())->save()) {
                return response()->json(['pet' => $pet], 200);
            }

            return response()->json(['message' => 'Pet Update Failed!'], 409);

        } catch (\Exception $e) {

            return response()->json(['message' => 'Pet Update Failed!'], 409);
        }
    }

    public function destroy($id)
    {
        $pet = Pet::where([
            ['id', $id],
            ['user_id', Auth::user()->id]
        ])->first();

        if(Pet::destroy($pet->id)) {
            return response()->json(['message' => 'Pet Deleted!'], 200);
        }
        return response()->json(['message' => 'Pet not found!'], 404);
    }

}
