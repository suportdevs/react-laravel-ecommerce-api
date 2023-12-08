<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function categories(Request $request) {
        $categories = Category::get();
        return response()->json(['categories', $categories]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }
        try{
            DB::beginTransaction();
            $data = Category::insert([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                '_key' => uniqueKey(),
            ]);
            DB::commit();
            return response()->json(['message' => 'Record created successfull.', 'data' => $data], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'unique', Rule::unique('categories')->ignore($id)],
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }
        try{
            DB::beginTransaction();
            $data = Category::where('id', $id)->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
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
            if(!Category::whereIn('id', $request->data)->delete()){
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
