<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::latest()->get();

        return new ApiResource(true, 'Address List', $addresses);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id'   => 'required',
            'receiver_name' => 'required',
            'address_name'  => 'required',
            'detail'        => 'required',
            'phone'         => 'required',
            'postal_code'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $address = Address::create([
            'customer_id'   => $request->customer_id,
            'receiver_name' => $request->receiver_name,
            'address_name'  => $request->address_name,
            'detail'        => $request->detail,
            'phone'         => $request->phone,
            'postal_code'   => $request->postal_code,
        ]);

        return new ApiResource(true, 'Address added!', $address);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'receiver_name' => 'required',
            'address_name'  => 'required',
            'detail'        => 'required',
            'phone'         => 'required',
            'postal_code'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $address = Address::find($id);
        $address->update([
            'receiver_name' => $request->receiver_name,
            'address_name'  => $request->address_name,
            'detail'        => $request->detail,
            'phone'         => $request->phone,
            'postal_code'   => $request->postal_code,
        ]);

        return new ApiResource(true, 'Address updated!', $address);
    }

    public function destroy(Address $address)
    {
        $address->delete();
        return new ApiResource(true, 'Address deleted!', null);
    }
}
