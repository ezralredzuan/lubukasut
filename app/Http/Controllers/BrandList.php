<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BrandList extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::query();

        if ($request->has('search')) {
            $query->where('BrandName', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 5);
        $brands = $query->paginate($perPage);

        return view('content.pages.brand-list', compact('brands'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'brandName' => 'required|unique:brands,BrandName|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Generate a unique BrandID between 1-100
        do {
            $brandID = rand(1, 100);
            $exists = Brand::where('BrandID', $brandID)->exists();
        } while ($exists);

        $brand = new Brand();
        $brand->BrandID = $brandID;
        $brand->BrandName = $request->brandName;
        $brand->created_at = now();
        $brand->updated_at = now();
        $brand->save();

        return response()->json(['message' => 'Brand added successfully!']);
    }

    public function destroy($id)
    {
        $brand = Brand::where('BrandID', $id)->first();

        if (!$brand) {
            return response()->json(['success' => false, 'message' => 'Brand not found.'], 404);
        }

        $brand->delete();
        return response()->json(['success' => true, 'message' => 'Brand deleted successfully.']);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'brandName' => 'required|string|max:255',
        ]);

        $brand = Brand::findOrFail($id);
        $brand->BrandName = $request->brandName;
        $brand->updated_at = now(); // Update timestamp
        $brand->save();

        return response()->json(['message' => 'Brand updated successfully!']);
    }
}