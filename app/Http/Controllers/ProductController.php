<?php

namespace App\Http\Controllers;

use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index']);
        $this->user = $this->guard()->user();
    }

    protected function guard()
    {
        return Auth::guard();
    }

    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required',
            'price' => 'required|numeric',            
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(storage_path('/app/public/images/products'), $imageName);
        } else {
            $imageName = 'default.png';
        }
        $request->image =  $imageName;
        
        $product = new Product();
        $product->title = $request->title;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->image = $request->image;
 
        if ($product->save()) {
            return response()->json([
                'status' => true,
                'product' => $product
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Product could not saved!"
            ]);
        }
    }
 
    public function update(Request $request, $id)
    {
        $product = Product::where('id', $id)->first();
      
         $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required',
            'price' => 'required|numeric',                 
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 400);
        }
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(storage_path('/app/public/images/products'), $imageName);  
            if($product->image != 'default.png') {
                 unlink(storage_path('/app/public/images/products/') . $product->image);
            }
           
        } else {
            $imageName = $product->image;     
        }
        $request->image =  $imageName;
        
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $request->image,
        ]; 
        if ($product->update($data)) {
            return response()->json([
                'status' => true,
                'product' => $product,
                'message' => 'Product has been updated!'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Product could not updated!'
            ]);
        }
    }

    public function destroy($id)
    {
        $product = Product::where('id', $id)->first();
        if($product->image != 'default.png') {
            unlink(storage_path('/app/public/images/products/') . $product->image);
       }

        if ($product->delete()) {
            return response()->json([
                'status' => true,
                'product' => 'Product has been deleted!'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Delete failed!'
            ]);
        }
    }
}
