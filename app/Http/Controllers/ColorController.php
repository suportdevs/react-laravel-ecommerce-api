<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Size;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ColorController extends Controller
{
    public function colors(Request $request) {
        $colors = Color::get();
        return response()->json(['colors' => $colors]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:colors',
            'color' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }
        try{
            DB::beginTransaction();
            $data = Color::insert([
                'name' => $request->name,
                'color' => $request->color,
                'description' => $request->description,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                '_key' => uniqueKey(),
            ]);
            DB::commit();
            return response()->json(['message' => 'Record created successfull', 'data' => $data], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function updated(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', Rule::unique('colors')->ignore($id)],
            'color' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()],422);
        }
        try{
            DB::beginTransaction();
            $data = Color::where('id', $id)->update([
                'name' => $request->name,
                'color' => $request->color,
                'description' => $request->description,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ]);
            DB::commit();
            return response()->json(['message' => 'Record updated successfull', 'data' => $data], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function delete(Request $request) {
        try{
            DB::beginTransaction();
            if(!Color::whereIn('id', $request->data)->delete()){
                throw new Exception('Something went wrong!');
            }
            DB::commit();
            return response()->json(['message' => 'Record deleted successfull'], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
