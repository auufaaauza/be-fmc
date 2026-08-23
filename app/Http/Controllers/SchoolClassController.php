<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    /**
     * Public endpoint: list active classes (for registration dropdown).
     */
    public function publicList(): JsonResponse
    {
        $classes = SchoolClass::where('is_active', true)
            ->orderBy('grade', 'desc')
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'grade', 'school_id']);

        return response()->json(['data' => $classes]);
    }

    /**
     * Public endpoint: list active classes for a specific school (for registration dropdown).
     */
    public function publicSchoolClasses(int $schoolId): JsonResponse
    {
        $school = School::findOrFail($schoolId);

        $classes = SchoolClass::where('school_id', $school->id)
            ->where('is_active', true)
            ->orderBy('grade', 'desc')
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'grade', 'school_id']);

        return response()->json(['data' => $classes]);
    }

    /**
     * Admin BK / Super Admin: list classes of their school.
     */
    public function index(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $query = SchoolClass::withCount('students');

        if ($user->role === 'admin') {
            if ($user->school_id) {
                $query->where(function ($q) use ($user) {
                    $q->where('school_id', $user->school_id)
                      ->orWhereNull('school_id');
                });
            }
        } elseif ($schoolId = $request->query('school_id')) {
            $query->where('school_id', $schoolId);
        }

        if ($search = $request->query('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $classes = $query->orderBy('grade', 'desc')->orderBy('name', 'asc')->get();

        return response()->json(['data' => $classes]);
    }

    /**
     * Admin BK / Super Admin: create class.
     */
    public function store(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $defaultSchool = School::where('name', 'SMAN 18 Garut')->first() ?? School::first();
        $schoolId = $user->role === 'admin'
            ? ($user->school_id ?: $defaultSchool?->id)
            : ($request->input('school_id') ?: $defaultSchool?->id);

        if (! $schoolId) {
            return response()->json(['message' => 'Sekolah wajib dipilih.'], 422);
        }

        $data = $request->validate([
            'name'      => ['required', 'string', 'max:50'],
            'grade'     => ['nullable', 'string', 'max:10'],
            'is_active' => ['boolean'],
        ]);

        // Check unique per school
        $exists = SchoolClass::where('school_id', $schoolId)
            ->where('name', $data['name'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Kelas dengan nama tersebut sudah ada di sekolah ini.'], 422);
        }

        $schoolClass = SchoolClass::create([
            'school_id' => $schoolId,
            'name'      => $data['name'],
            'grade'     => $data['grade'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return response()->json([
            'message' => 'Kelas berhasil ditambahkan.',
            'data'    => $schoolClass,
        ], 201);
    }

    /**
     * Admin BK / Super Admin: update class.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $schoolClass = SchoolClass::findOrFail($id);

        if ($user->role === 'admin' && $user->school_id && $schoolClass->school_id && $schoolClass->school_id !== $user->school_id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $data = $request->validate([
            'name'      => ['required', 'string', 'max:50'],
            'grade'     => ['nullable', 'string', 'max:10'],
            'is_active' => ['boolean'],
        ]);

        // Check unique per school excluding current
        $exists = SchoolClass::where('school_id', $schoolClass->school_id)
            ->where('name', $data['name'])
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Kelas dengan nama tersebut sudah ada di sekolah ini.'], 422);
        }

        $schoolClass->update($data);

        return response()->json([
            'message' => 'Kelas berhasil diperbarui.',
            'data'    => $schoolClass,
        ]);
    }

    /**
     * Admin BK / Super Admin: delete class.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $schoolClass = SchoolClass::findOrFail($id);

        if ($user->role === 'admin' && $user->school_id && $schoolClass->school_id && $schoolClass->school_id !== $user->school_id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        if ($schoolClass->students()->exists()) {
            return response()->json(['message' => 'Kelas tidak dapat dihapus karena masih ada siswa yang terdaftar di kelas ini.'], 422);
        }

        $schoolClass->delete();

        return response()->json(['message' => 'Kelas berhasil dihapus.']);
    }
}
