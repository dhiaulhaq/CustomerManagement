<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('addresses')->get();

        return new ApiResource(true, 'Customer List', $customers);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'photo'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email'         => 'required',
            'phone'         => 'required',
            'gender'        => 'required',
            'birth_date'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $photo = $request->file('photo');
        $photo->storeAs('public/customers', $photo->hashName());

        $customer = Customer::create([
            'name'          => $request->name,
            'photo'         => $photo->hashName(),
            'email'         => $request->email,
            'phone'         => $request->phone,
            'gender'        => $request->gender,
            'birth_date'    => $request->birth_date,
        ]);

        return new ApiResource(true, 'Customer added!', $customer);
    }

    public function show($id)
    {
        $customer = Customer::with('addresses')->find($id);

        return new ApiResource(true, 'Customer get!', $customer);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'email'         => 'required',
            'phone'         => 'required',
            'gender'        => 'required',
            'birth_date'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customer = Customer::find($id);

        if ($request->hasFile('photo')) {

            $photo = $request->file('photo');
            $photo->storeAs('public/customers', $photo->hashName());

            Storage::delete('public/customers/' . basename($customer->photo));

            $customer->update([
                'name'          => $request->name,
                'photo'         => $photo->hashName(),
                'email'         => $request->email,
                'phone'         => $request->phone,
                'gender'        => $request->gender,
                'birth_date'    => $request->birth_date,
            ]);
        } else {
            $customer->update([
                'name'          => $request->name,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'gender'        => $request->gender,
                'birth_date'    => $request->birth_date,
            ]);
        }

        return new ApiResource(true, 'Customer updated!', $customer);
    }

    public function destroy(Customer $customer)
    {
        Storage::delete('public/customers/' . $customer->image);

        $customer->delete();

        return new ApiResource(true, 'Customer deleted!', null);
    }

    public function search(Request $request)
    {
        $query = Customer::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->has('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        if ($request->has('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }
        if ($request->has('gender')) {
            $query->where('gender', $request->gender);
        }
        if ($request->has('birth_date')) {
            $query->whereDate('birth_date', $request->birth_date);
        }

        $customers = $query->with('addresses')->get();

        return new ApiResource(true, 'Customer found!', $customers);
    }
}
