<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
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
            'name' => 'required',
            'phone' => 'required',
            'access_level' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(["user_id" => null]);
        } else {
            $user = User::create([
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'access_level' => $request->get('access_level'),
                'password' => $request->get('password'),
                'mlearn_id' => $request->get('mlearn_id'),
                'active' => true,
            ]);
            return response()->json(["user_id" => $user->id], 201);
        }
    }

    public function updateAccessLevel(Request $request) {
        $user = User::find($request->get('id'));
        $user->access_level = $request->get('access_level');
        $user->save();
    }

    public function updateMLearnId(Request $request) {
        $user = User::find($request->get('id'));
        $user->mlearn_id = $request->get('mlearn_id');
        $user->save();
    }

}
