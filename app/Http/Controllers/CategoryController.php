<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
}
