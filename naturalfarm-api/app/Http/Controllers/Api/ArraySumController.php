<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArraySumController extends Controller
{
    public function calculateSum(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numbers' => 'required|array',
            'numbers.*' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $numbers = $request->input('numbers');

        $totalSum = array_sum($numbers);

        $result = array_map(function ($number) use ($totalSum) {
            return $totalSum - $number;
        }, $numbers);

        return new ApiResource(true, 'Calculating success!', $result);
    }
}
