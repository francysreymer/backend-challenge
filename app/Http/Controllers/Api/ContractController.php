<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Contract;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required',
            'kind_of_person' => 'required',
            'document' => 'required',
            'contractor_email' => 'required|email',
            'contractor_full_name' => 'required',
        ]);

        $property = DB::table('properties')
            ->where('id', $request->get('property_id'))
            ->where('active', true)
            ->where('hired', false)
            ->exists();

        if ($validator->fails() || !$property) {
            return response()->json(["contractor_id" => null]);
        } else {
            $contractor = Contract::create([
                'property_id' => $request->get('property_id'),
                'kind_of_person' => $request->get('kind_of_person'),
                'document' => $request->get('document'),
                'contractor_email' => $request->get('contractor_email'),
                'contractor_full_name' => $request->get('contractor_full_name'),
            ]);

            DB::table('properties')
                ->where('id', $request->get('property_id'))
                ->update(['hired' => true]);

            return response()->json(["contractor_id" => $contractor->id], 201);
        }
    }
}
