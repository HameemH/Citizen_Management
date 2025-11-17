<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FakeNid;
use Illuminate\Http\JsonResponse;

class FakeNidStatusController extends Controller
{
    /**
     * Return the Fake NID verification status for the provided number.
     */
    public function show(string $nidNumber): JsonResponse
    {
        if (!preg_match('/^\d{10}$/', $nidNumber)) {
            return response()->json([
                'nid_number' => $nidNumber,
                'status' => 'invalid_format',
            ], 422);
        }

        $statusInfo = FakeNid::getVerificationStatus($nidNumber);
        $status = $statusInfo['status'] ?? 'unknown';

        $httpStatus = $status === 'not_found' ? 404 : 200;

        return response()->json([
            'nid_number' => $nidNumber,
            'status' => $status,
        ], $httpStatus);
    }
}
