<?php

namespace App\Http\Controllers;

use App\Models\QuestionnaireAnswer;
use App\Models\QuestionnaireQuestion;
use App\Models\Recommendation;
use App\Models\School;
use App\Models\StudentScore;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ── Login ──────────────────────────────────────────────────────────────────

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'role'       => ['nullable', 'in:admin,student'],
            'identifier' => ['required', 'string'],
            'password'   => ['required', 'string'],
        ], [
            'identifier.required' => 'NISN atau Email wajib diisi.',
            'password.required'   => 'Password wajib diisi.',
        ]);

        $identifier = trim($data['identifier']);

        $query = User::where(function ($q) use ($identifier) {
            $q->where('email', $identifier)
              ->orWhere('nisn', $identifier);
        });

        if (! empty($data['role'])) {
            $query->where('role', $data['role']);
        }

        $user = $query->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Login gagal. NISN/Email atau password salah.'], 422);
        }

        if (! $user->is_active) {
            $msg = $data['role'] === 'admin'
                ? 'Akun Guru BK tidak aktif. Hubungi administrator sekolah.'
                : 'Akun tidak aktif. Hubungi Guru BK.';
            return response()->json(['message' => $msg], 403);
        }

        $user->tokens()->delete();
        $token = $user->createToken('find-my-career-web')->plainTextToken;

        return response()->json([
            'message'  => 'Login berhasil.',
            'token'    => $token,
            'user'     => $user->load('school'),
            'progress' => $this->progress($user->id),
        ]);
    }

    // ── Register ───────────────────────────────────────────────────────────────

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'role' => ['required', 'in:student'],
        ]);

        return $this->registerStudent($request);
    }

    private function registerStudent(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'nisn'          => ['required', 'string', 'max:20', 'unique:users,nisn'],
            'email'         => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'class'         => ['required', 'string', 'max:30'],
            'school_id'     => ['nullable', 'exists:schools,id'],
            'origin_school' => ['nullable', 'string', 'max:255'],
            'password'      => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'nisn.unique'        => 'NISN sudah terdaftar.',
            'email.unique'       => 'Email sudah digunakan.',
            'class.required'     => 'Silakan pilih kelas Anda.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $defaultSchool = School::where('name', 'SMAN 18 Garut')->first() ?? School::first();
        $schoolId = ! empty($data['school_id']) ? (int) $data['school_id'] : $defaultSchool?->id;

        $student = User::create([
            'name'          => $data['name'],
            'nisn'          => $data['nisn'],
            'email'         => $data['email'] ?? null,
            'class'         => $data['class'] ?? null,
            'school_id'     => $schoolId,
            'origin_school' => $data['origin_school'] ?? 'SMAN 18 Garut',
            'password'      => $data['password'],
            'role'          => 'student',
            'is_active'     => true,
        ]);

        return response()->json([
            'message' => 'Registrasi berhasil! Silakan login.',
            'data'    => $student->load('school'),
        ], 201);
    }

    // ── Logout / Me ────────────────────────────────────────────────────────────

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logout berhasil.']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user'     => $request->user()->load('school'),
            'progress' => $this->progress($request->user()->id),
        ]);
    }

    // ── Progress ───────────────────────────────────────────────────────────────

    private function progress(int $userId): array
    {
        $subjectTotal  = Subject::count();
        $questionTotal = QuestionnaireQuestion::count();

        $scoreCount  = StudentScore::where('user_id', $userId)->count();
        $answerCount = QuestionnaireAnswer::where('user_id', $userId)->count();

        return [
            'rapor_complete'          => $scoreCount >= 3,
            'questionnaire_complete'  => $questionTotal > 0 && $answerCount >= $questionTotal,
            'recommendation_complete' => Recommendation::where('user_id', $userId)->exists(),
            'score_count'             => $scoreCount,
            'answer_count'            => $answerCount,
            'subject_total'           => $subjectTotal,
            'question_total'          => $questionTotal,
        ];
    }
}
