<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class SizeController extends Controller
{
    public function sizes(Request $request) {
        try{
            $sizes = Size::get();
            return response()->json(['sizes' => $sizes]);
        }catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'=> 'required|string|unique:sizes',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }
        try{
            DB::beginTransaction();
            $size = Size::create([
                'name' => $request->name,
                'description'=> $request->description,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                '_key' => uniqueKey()
            ]);
            DB::commit();
            return response()->json(['message' => 'Record created success full', 'size' =>$size], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', Rule::unique('sizes')->ignore($id)],
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }
        try{
            DB::beginTransaction();
            $size = Size::where('id', $id)->update([
                'name' => $request->name,
                'description' => $request->description,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ]);
            DB::commit();
            return response()->json(['message' => 'Record updated success full'], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function delete(Request $request) {
        try{
            DB::beginTransaction();
            if(!Size::where('id', $request->data)->delete()){
                throw new Exception("Something went wrong!");
            }
            DB::commit();
            return response()->json(['message' => 'Record deleted successfull.'], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
