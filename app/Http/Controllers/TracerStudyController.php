<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TracerStudy;
use Validator;

class TracerStudyController extends Controller
{
    public function getData()
    {
        try {
            DB::beginTransaction();
            $result = DB::table('table_user_education')
                ->selectRaw('table_user_education.*')
                ->leftJoin('table_user_education_degree', 'table_user_education.degree_id', '=', 'table_user_education_degree.id')
                ->where('user_id', auth('user_api')->user()->id)
                ->first();
            $result->school = DB::table('table_school')
                ->selectRaw('name, phone, email, fax, address, website, logo, postal_code, about, mission, vision')
                ->where('id', $result->school_id)->first();
            $result->major = DB::table('table_user_education_major')
                ->where('id', $result->major_id)->first();
            $result->degree = DB::table('table_user_education_degree')
                ->where('id', $result->degree_id)->first();
            $result->tracer_study = DB::table('table_user_tracer_study')
                ->where('id', $result->school_id)->first();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage(), 'success' => false], 500);
        }
        return response()->json(['data' => $result, 'success' => true]);
    }

    public function addTracerStudy(Request $request)
    {
        // validate
        $validator = $this->tracerStudyValidate($request->all());
        if ($validator->fails()) {
            return response()->json($validator->errors());       
        }

        try {
            DB::beginTransaction();
            $data = TracerStudy::create([
                'school_id' => $request->school_id,
                'name' => $request->name,
                'description' => $request->description,
                'target_start' => $request->target_start,
                'target_end' => $request->target_end,
                'publication_start' => $request->publication_start,
                'publication_end' => $request->publication_end,
             ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage(), 'success' => false], 500);
        }
        return response()->json(['data' => $data, 'message' => 'success']);
    }

    public function updateTracerStudy(Request $request, $id)
    {
        // validate
        $validator = $this->tracerStudyValidate($request->all());
        if ($validator->fails()) {
            return response()->json($validator->errors());       
        }

        try {
            DB::beginTransaction();

            $data = TracerStudy::find($id);
            // cek if not found
            if (empty($data)) {
                return response()->json(['error' => 'data not found', 'success' => false], 404);
            }

            // update
            $data->school_id = $request->school_id;
            $data->name = $request->name;
            $data->description = $request->description;
            $data->target_start = $request->target_start;
            $data->target_end = $request->target_end;
            $data->publication_start = $request->publication_start;
            $data->publication_end = $request->publication_end;
            $data->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage(), 'success' => false], 500);
        }
        return response()->json(['data' => $data, 'message' => 'success']);
    }

    public function deleteTracerStudy($id)
    {
        $data = TracerStudy::find($id);
        // cek if not found
        if (empty($data)) {
            return response()->json(['error' => 'data not found', 'success' => false], 404);
        }
        $data->delete();

        return response()->json(['message' => 'success']);
    }

    // func helper
    protected function tracerStudyValidate($request)
    {
        $validator = Validator::make($request, [
            'school_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'target_start' => 'required|date_format:Y-m-d',
            'target_end' => 'required|date_format:Y-m-d',
            'publication_start' => 'required|date_format:Y-m-d',
            'publication_end' => 'required|date_format:Y-m-d',
        ]);

        return $validator;
    }
}
