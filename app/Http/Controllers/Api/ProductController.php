<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    use ApiResponseTrait;

    protected function index()
    {
        $products = Product::all();//Get All Product

        return $this->successResponse($products, "Products retrieved successfully");
    }

    protected function store(Request $request)
    {
        try {
            //Validation
            $validation = $this->validateRequest($request, [
                'name' => 'required',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'image' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048'
            ]);

            if ($validation) return $validation;

            $imagePath = $request->hasFile('image') ? $request->file('image')->store('products', 'public') : null;

            Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'image' => $imagePath
            ]);

            return $this->successResponse("Product created successfully", 201);
        } catch (\Exception $e) {
            return $this->errorResponse("Something went wrong", ['error' => $e->getMessage()], 500);
        }
    }
    protected function product_detail(Request $request)
    {
        try {
            $validation = $this->validateRequest($request, [
                'id' => 'required|exists:products,id',
            ]);

            if ($validation) return $validation;

            $id = $request->id;
            $productDetail = Product::find($id);

            if (!$productDetail) {
                return $this->errorResponse("Product not found", 404);
            }

            $productDetail->image = $productDetail->image ? asset('storage/' . $productDetail->image) : null; //image store in storage folder

            return $this->successResponse($productDetail, "Product retrieved successfully", 200);
        } catch (\Exception $e) {
            return $this->errorResponse("Something went wrong", ['error' => $e->getMessage()], 500);
        }
    }
    protected function product_update(Request $request)
    {
        try {
            $validation = $this->validateRequest($request, [
                'id' => 'required|exists:products,id',
                'name' => 'required',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048'
            ]);

            if ($validation) return $validation;

            $product = Product::find($request->id);

            if (!$product) {
                return $this->errorResponse("Product not found", 404);
            }

            if ($request->hasFile('image')) {
                // old image delete
                if ($product->image) {
                    Storage::delete('public/' . $product->image);
                }

                // new image store
                $product->image = $request->file('image')->store('products', 'public');
            }

            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'image' => $product->image ?? $product->image
            ]);

            return $this->successResponse("Product updated successfully", 200);
        } catch (\Exception $e) {
            return $this->errorResponse("Something went wrong", ['error' => $e->getMessage()], 500);
        }
    }
    //product delete
    protected function product_delete(Request $request)
    {
        try {
            $validation = $this->validateRequest($request, [
                'id' => 'required|exists:products,id',
            ]);

            if ($validation) return $validation;

            $product = Product::find($request->id);

            if (!$product) {
                return $this->errorResponse("Product not found", 404);
            }

            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }

            $product->delete();

            return $this->successResponse("Product deleted successfully", 200);
        } catch (\Exception $e) {
            return $this->errorResponse("Something went wrong", ['error' => $e->getMessage()], 500);
        }
    }
}
