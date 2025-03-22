<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotion;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PromotionListController extends Controller
{
    public function index()
    {
        $promotions = Promotion::orderBy('created_at', 'desc')->get();
        return view('content.pages.promotion-list', compact('promotions'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Name' => 'required|string|max:255',
            'PromotionCode' => 'required|string|max:50|unique:promotions',
            'DiscountPercentage' => 'required|numeric|min:0|max:100',
            'ValidUntilDate' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $promotion = Promotion::create([
            'Name' => $request->Name,
            'PromotionCode' => $request->PromotionCode,
            'DiscountPercentage' => $request->DiscountPercentage,
            'ValidUntilDate' => $request->ValidUntilDate,
            'StaffID' => 1, // Default StaffID = 1
        ]);

        return response()->json(['success' => 'Promotion added successfully!', 'promotion' => $promotion]);
    }

    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'Name' => 'required|string|max:255',
            'PromotionCode' => 'required|string|max:50|unique:promotions,PromotionCode,' . $id . ',PromotionId',
            'DiscountPercentage' => 'required|numeric|min:0|max:100',
            'ValidUntilDate' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $promotion->update([
            'Name' => $request->Name,
            'PromotionCode' => $request->PromotionCode,
            'DiscountPercentage' => $request->DiscountPercentage,
            'ValidUntilDate' => $request->ValidUntilDate,
        ]);

        return response()->json(['success' => 'Promotion updated successfully!', 'promotion' => $promotion]);
    }

    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();
        return response()->json(['success' => 'Promotion deleted successfully!']);
    }
}