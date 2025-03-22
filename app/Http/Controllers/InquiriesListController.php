<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiries;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class InquiriesListController extends Controller
{
    public function index(Request $request)
    {
        $query = Inquiries::query();

        if ($request->has('search')) {
            $query->where('InquiriesTitle', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 5);
        $inquiries = $query->paginate($perPage);

        return view('content.pages.inquiries-list', compact('inquiries'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'inquiriesTitle' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $inquiry = new Inquiries();
        $inquiry->Email = $request->email;
        $inquiry->InquiriesTitle = $request->inquiriesTitle;
        $inquiry->Description = $request->description;
        $inquiry->DateCreated = now();
        $inquiry->save();

        return response()->json(['message' => 'Inquiry added successfully!']);
    }

    public function destroy($id)
    {
        $inquiry = Inquiries::where('InquiriesID', $id)->first();

        if (!$inquiry) {
            return response()->json(['success' => false, 'message' => 'Inquiry not found.'], 404);
        }

        $inquiry->delete();
        return response()->json(['success' => true, 'message' => 'Inquiry deleted successfully.']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'inquiriesTitle' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        $inquiry = Inquiries::findOrFail($id);
        $inquiry->Email = $request->email;
        $inquiry->InquiriesTitle = $request->inquiriesTitle;
        $inquiry->Description = $request->description;
        $inquiry->save();

        return response()->json(['message' => 'Inquiry updated successfully!']);
    }
}
