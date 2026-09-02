<?php

namespace App\Services;

use App\Models\QuestionnaireAnswer;
use App\Models\StudentScore;
use App\Models\StudyProgram;

class SAWService
{
    public function calculate(int $userId): array
    {
        $studentScores = StudentScore::where('user_id', $userId)
            ->pluck('score', 'subject_id')
            ->toArray();

        $interestScores = $this->calculateInterestScores($userId);
        $programs = StudyProgram::with(['criteria.primarySubject', 'criteria.secondarySubject', 'criteria.interestCategory'])->get();

        // Matriks keputusan: setiap baris adalah program studi, kolomnya nilai utama, pendukung, dan minat.
        $matrix = [];
        foreach ($programs as $program) {
            $criteria = $program->criteria;

            $primaryScore = (float) ($studentScores[$criteria->primary_subject_id] ?? 0);
            $secondaryScore = $criteria->secondary_subject_id
                ? (float) ($studentScores[$criteria->secondary_subject_id] ?? 0)
                : null;
            $interestScore = (float) ($interestScores[$criteria->interest_category_id] ?? 0);

            $matrix[$program->id] = [
                'program' => $program,
                'primary_score' => $primaryScore,
                'secondary_score' => $secondaryScore,
                'interest_score' => $interestScore,
                'primary_weight' => (float) $criteria->primary_weight,
                'secondary_weight' => (float) $criteria->secondary_weight,
                'interest_weight' => (float) $criteria->interest_weight,
            ];
        }

        $results = [];
        foreach ($matrix as $data) {
            $rPrimary = min(1.0, max(0.0, $data['primary_score'] / 100));
            $rSecondary = $data['secondary_score'] !== null ? min(1.0, max(0.0, $data['secondary_score'] / 100)) : 0;
            // Normalisasi C3 (Minat RIASEC): 6 butir soal per kategori x skor maksimal 5 = 30
            $rInterest = min(1.0, max(0.0, $data['interest_score'] / 30));

            // Rumus SAW: Vi = jumlah dari bobot kriteria dikali nilai normalisasi.
            $vi = ($data['primary_weight'] * $rPrimary)
                + ($data['secondary_weight'] * $rSecondary)
                + ($data['interest_weight'] * $rInterest);

            $results[] = [
                'program' => $data['program'],
                'primary_score' => $data['primary_score'],
                'secondary_score' => $data['secondary_score'],
                'interest_score' => $data['interest_score'],
                'normalized_primary' => round($rPrimary, 6),
                'normalized_secondary' => round($rSecondary, 6),
                'normalized_interest' => round($rInterest, 6),
                'preference_value' => round($vi, 6),
            ];
        }

        usort($results, fn ($a, $b) => $b['preference_value'] <=> $a['preference_value']);

        foreach ($results as $rank => &$result) {
            $result['rank_position'] = $rank + 1;
        }

        return $results;
    }

    private function calculateInterestScores(int $userId): array
    {
        return QuestionnaireAnswer::where('user_id', $userId)
            ->join('questionnaire_questions', 'questionnaire_answers.question_id', '=', 'questionnaire_questions.id')
            ->selectRaw('questionnaire_questions.category_id, SUM(answer_score) as total_score')
            ->groupBy('questionnaire_questions.category_id')
            ->pluck('total_score', 'category_id')
            ->toArray();
    }
}
