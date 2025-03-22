<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $query = Staff::with('role');

        if ($request->has('search')) {
            $query->where('StaffName', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 5);
        $staffs = $query->paginate($perPage);
        $roles = Role::all(); // Fetch all roles

        return view('content.pages.staff-list', compact('staffs', 'roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'StaffName' => 'required|max:255',
            'Address' => 'required',
            'NoPhone' => 'required',
            'Email' => 'required|email|unique:staff,Email',
            'HiredDate' => 'required|date',
            'Role' => 'required|exists:roles,RoleID',
            'Username' => 'required|unique:staff,Username',
            'Password' => 'required|min:6',
            'StaffPic' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048' // Image validation

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $staff = new Staff();
        $staff->StaffName = $request->StaffName;
        $staff->Address = $request->Address;
        $staff->NoPhone = $request->NoPhone;
        $staff->Email = $request->Email;
        $staff->HiredDate = $request->HiredDate;
        $staff->Role = $request->Role;
        $staff->Username = $request->Username;
        $staff->Password = Hash::make($request->Password);

        if ($request->hasFile('StaffPic')) {
            $image = file_get_contents($request->file('StaffPic')->getRealPath());
            $staff->StaffPic = $image;
        }

        $staff->save();

        return response()->json(['message' => 'Staff added successfully!']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'StaffName' => 'required|max:255',
            'Address' => 'required',
            'NoPhone' => 'required',
            'Email' => 'required|email|unique:staff,Email,' . $id . ',StaffID',
            'HiredDate' => 'required|date',
            'Role' => 'required|exists:roles,RoleID',
            'Username' => 'required|unique:staff,Username,' . $id . ',StaffID',
            'Password' => 'nullable|min:6',
            'StaffPic' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048' // Image validation

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $staff = Staff::findOrFail($id);
        $staff->StaffName = $request->StaffName;
        $staff->Address = $request->Address;
        $staff->NoPhone = $request->NoPhone;
        $staff->Email = $request->Email;
        $staff->HiredDate = $request->HiredDate;
        $staff->Role = $request->Role;
        $staff->Username = $request->Username;
        if ($request->Password) {
            $staff->Password = Hash::make($request->Password);
        }

        if ($request->hasFile('StaffPic')) {
            $image = file_get_contents($request->file('StaffPic')->getRealPath());
            $staff->StaffPic = $image;
        }

        $staff->save();

        return response()->json(['message' => 'Staff updated successfully!']);
    }

    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();

        return response()->json(['message' => 'Staff deleted successfully!']);
    }

    public function edit($id)
    {
        $staff = Staff::findOrFail($id);

        if ($staff->StaffPic) {
            // Convert BLOB to Base64 format
            $imageData = base64_encode($staff->StaffPic);
            $staff->StaffPic = "data:image/jpeg;base64," . $imageData;
        } else {
            $staff->StaffPic = asset('default-user.png');
        }

        return response()->json($staff);
    }

    public function getStaffPic($id)
    {
        $staff = Staff::findOrFail($id);

        if ($staff->StaffPic) {
            return response($staff->StaffPic)->header('Content-Type', 'image/jpeg'); // Change based on image type
        } else {
            return response()->file(public_path('default-user.png'));
        }
    }
}