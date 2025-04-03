<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

trait ApiResponseTrait
{
    protected function successResponse($data = [], $message = 'Success', $status = 200): JsonResponse
    {
        return response()->json([
            'status'  => true,
            'message' => $message,
            'data'    => $data
        ], $status);
    }

    protected function errorResponse($message = 'Something went wrong', $status = 400, $data = []): JsonResponse
    {
        return response()->json([
            'status'  => false,
            'message' => $message,
            'data'    => $data
        ], $status);
    }

    protected function validateRequest($request, array $rules, array $messages = []): ?JsonResponse
    {
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $firstErrorMessage = collect($validator->errors()->all())->first();

            return response()->json([
                'status'  => false,
                'message' => $firstErrorMessage,
                'data'    => []
            ], 422);
        }

        return null;
    }
}
