<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Products;
use Validator;

class ProductsController extends Controller
{
    // Auth Guard
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['all_products']]);
    }

    // New Product Create
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:products',
            'slug' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'required',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $product = Products::create([
            'title' => $request['title'],
            'slug' => $request['slug'],
            'description' => $request['description'],
            'price' => $request['price'],
            'image' => $request['image'],
        ]);

        return response()->json([
            'message' => 'Product Created Successfully !',
            'product' => $product
        ], 201);
    }

    // All Products
    public function all_products(){
        $all_products = Products::all();
        return response()->json($all_products, 201);
    }

    // Product Read
    public function read($slug){
        $read = Products::where('slug', $slug)->firstorfail();
        return response()->json($read, 201);
    }

    // Product Update
    public function update(Request $request, $slug){
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:products',
            'description' => 'required',
            'price' => 'required',
            'image' => 'required',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $update = Products::where('slug', $slug)
        ->update([
            'title' => $request['title'],
            'description' => $request['description'],
            'price' => $request['price'],
            'image' => $request['image'],
            ]);

        $updated_product = Products::where('slug', $slug)->firstorfail();
        return response()->json([
            'message' => 'Product Updated Successfully !',
            'updatedProduct' => $updated_product
        ]);
    }

    // Product Delete
    public function delete($slug){
        $delete = Products::where('slug', $slug)->delete();
        return response()->json(['message' => 'Product Deleted Successfully !'], 201);
    }
}
