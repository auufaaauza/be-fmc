<?php

namespace App\Http\Controllers;

use App\Exports\StudentsExport;
use App\Exports\StudentsImportTemplateExport;
use App\Imports\StudentsImport;
use App\Models\InterestCategory;
use App\Models\QuestionnaireAnswer;
use App\Models\QuestionnaireQuestion;
use App\Models\Recommendation;
use App\Models\RecommendationResult;
use App\Models\StudentScore;
use App\Models\StudyProgram;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function listStudents(Request $request): JsonResponse
    {
        /** @var User $admin */
        $admin = $request->user();

        $query = User::where('role', 'student')
            ->withCount(['scores', 'questionnaireAnswers', 'recommendations'])
            ->with('school');

        // Admin (Guru BK) hanya melihat siswa dari sekolahnya sendiri atau siswa tanpa school_id (default)
        if ($admin->role === 'admin' && $admin->school_id) {
            $query->where(function ($q) use ($admin) {
                $q->where('school_id', $admin->school_id)
                  ->orWhereNull('school_id');
            });
        }

        if ($class = $request->query('class')) {
            $query->where('class', $class);
        }

        if ($search = $request->query('search')) {
            $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('nisn', 'like', "%{$search}%")
                ->orWhere('class', 'like', "%{$search}%"));
        }

        $questionTotal = QuestionnaireQuestion::count();

        $students = $query->orderBy('name')->get()->map(fn (User $student) => $this->withStatus($student, $questionTotal));

        return response()->json(['data' => $students]);
    }

    public function createStudent(Request $request): JsonResponse
    {
        /** @var User $admin */
        $admin = $request->user();

        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'nisn'      => ['required', 'string', 'max:20', 'unique:users,nisn'],
            'email'     => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'class'     => ['nullable', 'string', 'max:30'],
            'password'  => ['required', 'string', 'min:6'],
            'is_active' => ['boolean'],
        ]);

        $defaultSchool = School::where('name', 'SMAN 18 Garut')->first() ?? School::first();
        $schoolId = $admin->role === 'admin'
            ? ($admin->school_id ?: $defaultSchool?->id)
            : ($request->input('school_id') ?: $defaultSchool?->id);

        $student = User::create($data + [
            'role'      => 'student',
            'school_id' => $schoolId,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return response()->json(['message' => 'Siswa berhasil ditambahkan.', 'data' => $student], 201);
    }

    public function updateStudent(Request $request, int $id): JsonResponse
    {
        /** @var User $admin */
        $admin = $request->user();

        $query = User::where('role', 'student');
        if ($admin->role === 'admin' && $admin->school_id) {
            $query->where(function ($q) use ($admin) {
                $q->where('school_id', $admin->school_id)
                  ->orWhereNull('school_id');
            });
        }
        $student = $query->findOrFail($id);

        $data = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'nisn'      => ['required', 'string', 'max:20', 'unique:users,nisn,'.$student->id],
            'email'     => ['nullable', 'email', 'max:255', 'unique:users,email,'.$student->id],
            'class'     => ['nullable', 'string', 'max:30'],
            'password'  => ['nullable', 'string', 'min:6'],
            'is_active' => ['boolean'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $student->update($data);

        return response()->json(['message' => 'Data siswa berhasil diperbarui.', 'data' => $student]);
    }

    public function deleteStudent(Request $request, int $id): JsonResponse
    {
        /** @var User $admin */
        $admin = $request->user();

        $query = User::where('role', 'student');
        if ($admin->role === 'admin' && $admin->school_id) {
            $query->where(function ($q) use ($admin) {
                $q->where('school_id', $admin->school_id)
                  ->orWhereNull('school_id');
            });
        }
        $student = $query->findOrFail($id);
        $student->delete();

        return response()->json(['message' => 'Siswa berhasil dihapus.']);
    }

    /**
     * Reset student password to default ('siswa123') by BK Teacher.
     */
    public function resetPassword(Request $request, int $id): JsonResponse
    {
        /** @var User $admin */
        $admin = $request->user();

        $query = User::where('role', 'student');
        if ($admin->role === 'admin' && $admin->school_id) {
            $query->where(function ($q) use ($admin) {
                $q->where('school_id', $admin->school_id)
                  ->orWhereNull('school_id');
            });
        }
        $student = $query->findOrFail($id);

        $defaultPassword = 'siswa123';
        $student->update(['password' => $defaultPassword]);

        return response()->json([
            'message' => "Password siswa {$student->name} (NISN: {$student->nisn}) berhasil di-reset menjadi '{$defaultPassword}'.",
        ]);
    }

    public function studentRecommendation(Request $request, int $id): JsonResponse
    {
        /** @var User $admin */
        $admin = $request->user();

        $query = User::where('role', 'student')
            ->with([
                'school',
                'scores.subject',
                'questionnaireAnswers.question.category',
            ]);

        if ($admin->role === 'admin' && $admin->school_id) {
            $query->where(function ($q) use ($admin) {
                $q->where('school_id', $admin->school_id)
                  ->orWhereNull('school_id');
            });
        }

        $student = $query->findOrFail($id);

        $recommendation = Recommendation::where('user_id', $student->id)
            ->latest('calculated_at')
            ->with([
                'counselor',
                'results.program.criteria.primarySubject',
                'results.program.criteria.secondarySubject',
                'results.program.criteria.interestCategory',
            ])
            ->first();

        return response()->json([
            'student'        => $student,
            'scores'         => $student->scores,
            'answers'        => $student->questionnaireAnswers,
            'data'           => $recommendation,
        ]);
    }

    public function saveCounselorNote(Request $request, int $id): JsonResponse
    {
        /** @var User $admin */
        $admin = $request->user();

        $query = User::where('role', 'student');
        if ($admin->role === 'admin' && $admin->school_id) {
            $query->where(function ($q) use ($admin) {
                $q->where('school_id', $admin->school_id)
                  ->orWhereNull('school_id');
            });
        }
        $student = $query->findOrFail($id);

        $validated = $request->validate([
            'counselor_notes' => ['required', 'string', 'max:2000'],
        ]);

        $recommendation = Recommendation::where('user_id', $student->id)
            ->latest('calculated_at')
            ->firstOrFail();

        $recommendation->update([
            'counselor_notes'       => $validated['counselor_notes'],
            'counselor_id'          => $admin->id,
            'counselor_reviewed_at' => now(),
            'is_validated'          => true,
        ]);

        return response()->json([
            'message' => 'Catatan dan validasi rekomendasi berhasil disimpan.',
            'data'    => $recommendation->load([
                'results.program.criteria.primarySubject',
                'results.program.criteria.secondarySubject',
                'results.scores.subject',
                'counselor',
            ]),
        ]);
    }

    public function stats(Request $request): JsonResponse
    {
        /** @var User $admin */
        $admin = $request->user();

        $questionTotal = QuestionnaireQuestion::count();

        $studentQuery = User::where('role', 'student')
            ->withCount(['scores', 'questionnaireAnswers', 'recommendations']);

        if ($admin->role === 'admin' && $admin->school_id) {
            $studentQuery->where(function ($q) use ($admin) {
                $q->where('school_id', $admin->school_id)
                  ->orWhereNull('school_id');
            });
        }

        $students = $studentQuery->get();
        $studentIds = $students->pluck('id');

        // Recent recommendations
        $recent = Recommendation::whereIn('user_id', $studentIds)
            ->with(['user', 'results.program'])
            ->latest('calculated_at')
            ->limit(8)
            ->get();

        // 1. RIASEC Distribution Analytics (Optimized single aggregation query)
        $categories = InterestCategory::orderBy('id')->get();
        $categoryScores = QuestionnaireAnswer::whereIn('questionnaire_answers.user_id', $studentIds)
            ->join('questionnaire_questions', 'questionnaire_answers.question_id', '=', 'questionnaire_questions.id')
            ->select('questionnaire_questions.category_id', DB::raw('SUM(questionnaire_answers.answer_score) as total_score'))
            ->groupBy('questionnaire_questions.category_id')
            ->pluck('total_score', 'category_id');

        $riasecData = $categories->map(fn ($cat) => [
            'id'          => $cat->id,
            'name'        => $cat->name,
            'icon'        => $cat->icon,
            'total_score' => (int) ($categoryScores[$cat->id] ?? 0),
        ]);

        // 2. Top 5 Most Recommended Programs (Rank 1 / Top 3)
        $latestRecIds = Recommendation::whereIn('user_id', $studentIds)
            ->groupBy('user_id')
            ->selectRaw('MAX(id) as id')
            ->pluck('id');

        $topPrograms = RecommendationResult::whereIn('recommendation_id', $latestRecIds)
            ->where('rank_position', '<=', 3)
            ->select('program_id', DB::raw('COUNT(*) as total_recommended'), DB::raw('AVG(preference_value) as avg_score'))
            ->groupBy('program_id')
            ->orderByDesc('total_recommended')
            ->limit(5)
            ->with('program')
            ->get()
            ->map(function ($item) {
                return [
                    'program_id'        => $item->program_id,
                    'program_name'      => $item->program?->name,
                    'faculty'           => $item->program?->faculty,
                    'total_recommended' => (int) $item->total_recommended,
                    'avg_score'         => round((float) $item->avg_score, 4),
                ];
            });

        return response()->json([
            'data' => [
                'total_students'           => $students->count(),
                'rapor_complete'            => $students->where('scores_count', '>=', 3)->count(),
                'questionnaire_complete'    => $students->where('questionnaire_answers_count', '>=', $questionTotal)->count(),
                'recommendation_complete'   => $students->where('recommendations_count', '>', 0)->count(),
                'recent_recommendations'   => $recent,
                'riasec_distribution'      => $riasecData,
                'top_recommended_programs' => $topPrograms,
            ],
        ]);
    }

    // ── Export / Import ────────────────────────────────────────────────────────

    /**
     * Export daftar siswa beserta nilai rapor ke file Excel.
     * GET /admin/students/export
     */
    public function exportStudents(Request $request)
    {
        /** @var User $admin */
        $admin = $request->user();

        if (! $admin->school_id) {
            return response()->json(['message' => 'Admin tidak terhubung ke sekolah.'], 422);
        }

        $filename = 'data-siswa-nilai_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new StudentsExport($admin->school_id), $filename);
    }

    /**
     * Download template Excel kosong untuk import.
     * GET /admin/students/import-template
     */
    public function downloadImportTemplate()
    {
        $filename = 'template-import-siswa.xlsx';
        return Excel::download(new StudentsImportTemplateExport(), $filename);
    }

    /**
     * Import data siswa + nilai dari file Excel yang diupload.
     * POST /admin/students/import
     */
    public function importStudents(Request $request): JsonResponse
    {
        /** @var User $admin */
        $admin = $request->user();

        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'], // max 5MB
        ]);

        if (! $admin->school_id) {
            return response()->json(['message' => 'Admin tidak terhubung ke sekolah.'], 422);
        }

        $import = new StudentsImport($admin->school_id);
        Excel::import($import, $request->file('file'));

        return response()->json([
            'message' => 'Import selesai.',
            'summary' => [
                'total_processed' => count($import->results),
                'total_errors'    => count($import->errors),
            ],
            'results' => $import->results,
            'errors'  => $import->errors,
        ]);
    }

    private function withStatus(User $student, int $questionTotal = 0): array
    {
        // Cek rekomendasi terbaru secara aman
        $latestRec = Recommendation::where('user_id', $student->id)
            ->latest('calculated_at')
            ->first();

        $isValidated = false;
        if ($latestRec) {
            $isValidated = (bool) (
                $latestRec->is_validated ??
                ($latestRec->counselor_reviewed_at && $latestRec->counselor_notes)
            );
        }

        return [
            'id'                      => $student->id,
            'name'                    => $student->name,
            'nisn'                    => $student->nisn,
            'email'                   => $student->email,
            'class'                   => $student->class,
            'school_id'               => $student->school_id,
            'school_name'             => $student->school?->name,
            'is_active'               => $student->is_active,
            'rapor_complete'          => $student->scores_count >= 3,
            'questionnaire_complete'  => $questionTotal > 0 ? $student->questionnaire_answers_count >= $questionTotal : false,
            'recommendation_complete' => $student->recommendations_count > 0,
            'is_validated'            => $isValidated,
        ];
    }
}
