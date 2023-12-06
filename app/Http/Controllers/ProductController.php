<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function products(Request $request) {
        $products =Product::get();
        return response()->json(['products' => $products]);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'required',
            'categories' => 'required',
            'colors' => 'required',
            'sizes' => 'required',
            'rate' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }
        try{
            DB::beginTransaction();
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'image' => $request->image,
                'categories' => $request->categories,
                'colors' => $request->colors,
                'sizes' => $request->sizes,
                'rate' => $request->rate,
                '_key' => uniqueKey(),
                'created_by' => Auth::id(),
            ]);
            DB::commit();
            return response()->json(['message' => "Product created successfull.", 'product' => $product], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'required',
            'categories' => 'required',
            'colors' => 'required',
            'sizes' => 'required',
            'rate' => 'required|number',
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }
       try{
            DB::beginTransaction();
            $product = Product::find($id)->update([
                'name' => $request->name,
                'description' => $request->description,
                'image' => $request->image,
                'categories' => $request->categories,
                'colors' => $request->colors,
                'sizes' => $request->sizes,
                'rate' => $request->rate,
                'updated_by' => Auth::id(),
            ]);
            DB::commit();
            return response()->json(['message' => "Product created successfull.", 'product' => $product], 201);
       }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], $e->getCode());
       }
    }

    public function delete(Request $request) {
        try{
            DB::beginTransaction();
            if(!Product::whereIn('id', $request->data)->delete()){
                throw new \Exception("Something went wrong!");
            }
            DB::commit();
            return response()->json(['message' => 'Record deleted successfull.'], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}