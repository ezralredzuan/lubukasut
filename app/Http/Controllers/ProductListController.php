<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Response;
use App\Models\Brand;

class ProductListController extends Controller
{
    public function index(Request $request)
    {
      $query = Product::query();

      // Join brands table to get the Brand Name
      $query->join('brands', 'products.BrandID', '=', 'brands.BrandID')
            ->select('products.*', 'brands.BrandName');

      // Check if a search query is provided
      if ($request->has('search') && !empty($request->search)) {
          $searchTerm = $request->search;

          $query->where(function ($q) use ($searchTerm) {
              $q->where('products.Name', 'LIKE', '%' . $searchTerm . '%')  // Search Product Name
                ->orWhere('products.ProductID', 'LIKE', '%' . $searchTerm . '%') // Search Product ID
                ->orWhere('brands.BrandName', 'LIKE', '%' . $searchTerm . '%');  // Search Brand Name
          });
      }

      $products = $query->paginate($request->per_page ?? 10)->appends([
        'search' => $request->search,
        'per_page' => $request->per_page
    ]);
    $brands = Brand::all();
    return view('content.pages.product-list', compact('products', 'brands'));
    }

    public function store(Request $request)
    {

        // Validate input
        $request->validate([
            'name' => 'required|string',
            'brandID' => 'required|exists:brands,BrandID',
            'gender' => 'required|string',
            'size' => 'required',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'productImage' => 'required|image|mimes:jpeg,png,jpg,gif,webp,bmp,svg',
            'staffID' => 'required|integer',
        ]);

        // Handle Image Upload for BLOB storage
        $imageData = file_get_contents($request->file('productImage')->getRealPath());

        // Create new Product
        $product = new Product();
        $product->ProductID = $request->productID;
        $product->Name = $request->name;
        $product->BrandID = $request->brandID;
        $product->Gender = $request->gender;
        $product->Size = $request->size;
        $product->Price = $request->price;
        $product->Description = $request->description;
        $product->StaffID = $request->staffID;
        $product->ProductImage = $imageData; // Store as BLOB
        $product->save();

        return response()->json([
          'message' => 'Success!',
          'product' => $product->makeHidden('ProductImage') // Exclude the BLOB data from JSON response
      ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'brandID' => 'required|exists:brands,BrandID',
            'gender' => 'required|string',
            'size' => 'required',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'productImage' => 'required|image|mimes:jpeg,png,jpg,gif,webp,bmp,svg',
            'staffID' => 'required|integer',
        ]);

        $product = Product::findOrFail($id);
        $product->Name = $request->name;
        $product->BrandID = $request->brandID;
        $product->Gender = $request->gender;
        $product->Size = $request->size;
        $product->Price = $request->price;
        $product->Description = $request->description;
        $product->StaffID = $request->staffID;

        // Update image if provided
        if ($request->hasFile('productImage')) {
            $product->ProductImage = file_get_contents($request->file('productImage')->getRealPath());
        }

        $product->save();

        return response()->json([
          'message' => 'Product updated successfully!',
          'product' => $product->makeHidden('ProductImage')
        ]);
    }

    public function getProductImage($id)
    {
        $product = Product::findOrFail($id);

        if ($product->ProductImage) {
            return response($product->ProductImage)->header('Content-Type', 'image/jpeg');
        }

        return response()->json(['message' => 'Image not found'], 404);
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Product deleted successfully.']);
    }
}
