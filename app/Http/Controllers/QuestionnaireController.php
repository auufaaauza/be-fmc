<?php

namespace App\Http\Controllers;

use App\Models\InterestCategory;
use App\Models\QuestionnaireAnswer;
use App\Models\QuestionnaireQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categories = InterestCategory::with(['questions' => fn ($query) => $query->orderBy('order_num')])
            ->orderBy('id')
            ->get();

        $answers = QuestionnaireAnswer::where('user_id', $request->user()->id)
            ->pluck('answer_score', 'question_id');

        return response()->json(['data' => $categories, 'answers' => $answers]);
    }

    public function categories(): JsonResponse
    {
        $categories = InterestCategory::orderBy('id')->get();
        return response()->json(['data' => $categories]);
    }

    public function saveAnswers(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'answers' => ['required', 'array'],
            'answers.*.question_id' => ['required', 'exists:questionnaire_questions,id'],
            'answers.*.answer_score' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        foreach ($validated['answers'] as $answer) {
            QuestionnaireAnswer::updateOrCreate(
                ['user_id' => $request->user()->id, 'question_id' => $answer['question_id']],
                ['answer_score' => $answer['answer_score']]
            );
        }

        $complete = QuestionnaireAnswer::where('user_id', $request->user()->id)->count() >= QuestionnaireQuestion::count();

        return response()->json([
            'message' => 'Jawaban kuesioner berhasil disimpan.',
            'complete' => $complete,
        ]);
    }
}
