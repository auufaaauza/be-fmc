<?php

namespace App\Http\Controllers;

use App\Models\QuestionnaireAnswer;
use App\Models\QuestionnaireQuestion;
use App\Models\Recommendation;
use App\Models\RecommendationResult;
use App\Models\StudentScore;
use App\Models\Subject;
use App\Services\SAWService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecommendationController extends Controller
{
    public function calculate(Request $request, SAWService $sawService): JsonResponse
    {
        $userId = $request->user()->id;

        if (StudentScore::where('user_id', $userId)->count() < 3) {
            return response()->json(['message' => 'Silakan isi nilai rapor minimal untuk 3 mata pelajaran yang Anda pelajari.'], 422);
        }

        if (QuestionnaireAnswer::where('user_id', $userId)->count() < QuestionnaireQuestion::count()) {
            return response()->json(['message' => 'Lengkapi seluruh kuesioner terlebih dahulu.'], 422);
        }

        $recommendation = DB::transaction(function () use ($userId, $sawService) {
            $recommendation = Recommendation::create([
                'user_id' => $userId,
                'calculated_at' => now(),
            ]);

            foreach ($sawService->calculate($userId) as $row) {
                RecommendationResult::create([
                    'recommendation_id' => $recommendation->id,
                    'program_id' => $row['program']->id,
                    'primary_score' => $row['primary_score'],
                    'secondary_score' => $row['secondary_score'],
                    'interest_score' => $row['interest_score'],
                    'normalized_primary' => $row['normalized_primary'],
                    'normalized_secondary' => $row['normalized_secondary'],
                    'normalized_interest' => $row['normalized_interest'],
                    'preference_value' => $row['preference_value'],
                    'rank_position' => $row['rank_position'],
                ]);
            }

            return $recommendation;
        });

        return response()->json([
            'message' => 'Rekomendasi berhasil dihitung.',
            'data' => $this->loadRecommendation($recommendation->id),
        ], 201);
    }

    public function myHistory(Request $request): JsonResponse
    {
        $history = Recommendation::where('user_id', $request->user()->id)
            ->latest('calculated_at')
            ->with(['results.program'])
            ->get();

        return response()->json(['data' => $history]);
    }

    public function latest(Request $request): JsonResponse
    {
        $recommendation = Recommendation::where('user_id', $request->user()->id)
            ->latest('calculated_at')
            ->first();

        return response()->json([
            'data' => $recommendation ? $this->loadRecommendation($recommendation->id) : null,
        ]);
    }

    private function loadRecommendation(int $id): Recommendation
    {
        return Recommendation::with([
            'counselor',
            'results.program.criteria.primarySubject',
            'results.program.criteria.secondarySubject',
            'results.program.criteria.interestCategory',
        ])->findOrFail($id);
    }
}
