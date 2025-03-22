<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerListController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::query();

        if ($request->has('search')) {
            $customers->where('FullName', 'like', '%' . $request->search . '%');
        }

        return view('content.pages.customer-list', ['customers' => $customers->paginate(10)]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Username' => 'required|string|max:255|unique:customers,Username',
            'Password' => 'required|string|min:6',
            'FullName' => 'required|string|max:255',
            'NoPhone' => 'required|string|max:20',
            'Email' => 'required|email|max:255|unique:customers,Email',
            'Address' => 'required|string',
            'City' => 'required|string|max:100',
            'State' => 'required|string|max:100',
            'PostalCode' => 'required|string|max:10',
        ]);

        // Hash Password before storing
        $validatedData['Password'] = bcrypt($validatedData['Password']);

        $customer = Customer::create($validatedData);

        return response()->json($customer, 201);
    }


    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'Username' => 'required|unique:customers,Username,' . $id . ',CustomerID',
            'FullName' => 'required',
            'NoPhone' => 'required',
            'Email' => 'required|email|unique:customers,Email,' . $id . ',CustomerID',
            'Address' => 'required',
            'City' => 'required',
            'State' => 'required',
            'PostalCode' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $customer->update([
            'Username' => $request->Username,
            'FullName' => $request->FullName,
            'NoPhone' => $request->NoPhone,
            'Email' => $request->Email,
            'Address' => $request->Address,
            'City' => $request->City,
            'State' => $request->State,
            'PostalCode' => $request->PostalCode,
            'updated_at' => now()
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json(['success' => true]);
    }
}