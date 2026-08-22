<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\JsonResponse;

class SchoolController extends Controller
{
    /**
     * Public endpoint: list all active schools (for registration dropdown).
     */
    public function publicList(): JsonResponse
    {
        $schools = School::where('is_active', true)
            ->orderBy('province')
            ->orderBy('city')
            ->orderBy('name')
            ->get(['id', 'name', 'npsn', 'city', 'province']);

        return response()->json(['data' => $schools]);
    }
}
