<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\User;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    public function index()
    {
        $users = DB::table('users')
            ->where('active', true)->get();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'owner_email' => 'required|email',
            'address' => 'required',
            'neighborhood' => 'required',
            'city' => 'required',
            'state' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(["property_id" => null]);
        } else {
            $property = User::create([
                'owner_email' => $request->get('owner_email'),
                'address' => $request->get('address'),
                'number' => $request->get('number'),
                'complement' => $request->get('complement'),
                'neighborhood' => $request->get('neighborhood'),
                'city' => $request->get('city'),
                'state' => $request->get('state'),
                'active' => true,
            ]);
            return response()->json(["property_id" => $property->id], 201);
        }
    }

}
