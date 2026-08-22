<?php

namespace App\Http\Controllers;

use App\Models\StudentScore;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentScoreController extends Controller
{
    public function myScores(Request $request): JsonResponse
    {
        $scores = StudentScore::with('subject')
            ->where('user_id', $request->user()->id)
            ->get();

        return response()->json(['data' => $scores]);
    }

    public function saveScores(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'scores'               => ['required', 'array'],
            'scores.*.subject_id'  => ['required', 'exists:subjects,id'],
            'scores.*.sem1'        => ['nullable', 'numeric', 'min:0', 'max:100'],
            'scores.*.sem2'        => ['nullable', 'numeric', 'min:0', 'max:100'],
            'scores.*.sem3'        => ['nullable', 'numeric', 'min:0', 'max:100'],
            'scores.*.sem4'        => ['nullable', 'numeric', 'min:0', 'max:100'],
            'scores.*.sem5'        => ['nullable', 'numeric', 'min:0', 'max:100'],
            'scores.*.score'       => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $userId = $request->user()->id;

        foreach ($validated['scores'] as $item) {
            $semValues = [];
            foreach (['sem1', 'sem2', 'sem3', 'sem4', 'sem5'] as $semKey) {
                if (isset($item[$semKey]) && $item[$semKey] !== '' && $item[$semKey] !== null) {
                    $semValues[$semKey] = (float) $item[$semKey];
                } else {
                    $semValues[$semKey] = null;
                }
            }

            $filledSemValues = array_filter($semValues, fn ($v) => $v !== null);

            if (count($filledSemValues) > 0) {
                // Hitung rata-rata otomatis dari semester yang terisi
                $avgScore = array_sum($filledSemValues) / count($filledSemValues);

                StudentScore::updateOrCreate(
                    ['user_id' => $userId, 'subject_id' => $item['subject_id']],
                    [
                        'sem1'  => $semValues['sem1'],
                        'sem2'  => $semValues['sem2'],
                        'sem3'  => $semValues['sem3'],
                        'sem4'  => $semValues['sem4'],
                        'sem5'  => $semValues['sem5'],
                        'score' => round($avgScore, 2),
                    ]
                );
            } elseif (! empty($item['score']) && is_numeric($item['score'])) {
                // Jika hanya nilai rata-rata langsung yang diisi
                StudentScore::updateOrCreate(
                    ['user_id' => $userId, 'subject_id' => $item['subject_id']],
                    [
                        'sem1'  => null,
                        'sem2'  => null,
                        'sem3'  => null,
                        'sem4'  => null,
                        'sem5'  => null,
                        'score' => round((float) $item['score'], 2),
                    ]
                );
            } else {
                // Dikosongkan, hapus data mapel ini
                StudentScore::where('user_id', $userId)
                    ->where('subject_id', $item['subject_id'])
                    ->delete();
            }
        }

        $savedCount = StudentScore::where('user_id', $userId)->count();
        $complete = $savedCount >= 3;

        return response()->json([
            'message'     => 'Nilai rapor semester 1-5 berhasil disimpan.',
            'saved_count' => $savedCount,
            'complete'    => $complete,
        ]);
    }
}
