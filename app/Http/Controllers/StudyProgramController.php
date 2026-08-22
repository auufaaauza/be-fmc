<?php

namespace App\Http\Controllers;

use App\Models\StudyProgram;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StudyProgramController extends Controller
{
    public function index(): JsonResponse
    {
        $programs = StudyProgram::with([
            'criteria.primarySubject',
            'criteria.secondarySubject',
            'criteria.interestCategory',
        ])->orderBy('name')->get();

        return response()->json(['data' => $programs]);
    }

    public function show(int $id): JsonResponse
    {
        $program = StudyProgram::with([
            'criteria.primarySubject',
            'criteria.secondarySubject',
            'criteria.interestCategory',
        ])->findOrFail($id);

        return response()->json(['data' => $program]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateProgram($request);

        $program = DB::transaction(function () use ($validated) {
            $criteria = $validated['criteria'];
            unset($validated['criteria']);

            $program = StudyProgram::create($validated);
            $program->criteria()->create($criteria);

            return $program;
        });

        return response()->json(['message' => 'Program studi berhasil ditambahkan.', 'data' => $program->load('criteria')], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $program = StudyProgram::findOrFail($id);
        $validated = $this->validateProgram($request, false);

        DB::transaction(function () use ($program, $validated) {
            $program->update(collect($validated)->except('criteria')->toArray());

            if (isset($validated['criteria'])) {
                $program->criteria()->updateOrCreate(['program_id' => $program->id], $validated['criteria']);
            }
        });

        return response()->json(['message' => 'Program studi berhasil diperbarui.', 'data' => $program->fresh()->load('criteria')]);
    }

    private function validateProgram(Request $request, bool $requireCriteria = true): array
    {
        $criteriaRule = $requireCriteria ? 'required' : 'sometimes';

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'faculty' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'career_paths' => ['nullable', 'array'],
            'career_paths.*' => ['string'],
            'learning_path' => ['nullable', 'array'],
            'criteria' => [$criteriaRule, 'array'],
            'criteria.primary_subject_id' => [$criteriaRule, 'exists:subjects,id'],
            'criteria.primary_weight' => [$criteriaRule, 'numeric', 'min:0', 'max:1'],
            'criteria.secondary_subject_id' => ['nullable', 'exists:subjects,id'],
            'criteria.secondary_weight' => ['nullable', 'numeric', 'min:0', 'max:1'],
            'criteria.interest_category_id' => [$criteriaRule, 'exists:interest_categories,id'],
            'criteria.interest_weight' => [$criteriaRule, 'numeric', 'min:0', 'max:1'],
        ]);

        if (isset($validated['criteria'])) {
            $criteria = $validated['criteria'];
            $totalWeight = round(
                (float) ($criteria['primary_weight'] ?? 0)
                + (float) ($criteria['secondary_weight'] ?? 0)
                + (float) ($criteria['interest_weight'] ?? 0),
                2
            );

            // Total bobot SAW wajib 1.00 agar hasil rekomendasi konsisten.
            if ($totalWeight !== 1.00) {
                throw ValidationException::withMessages([
                    'criteria' => ['Total bobot kriteria harus 1.00.'],
                ]);
            }
        }

        return $validated;
    }
}
