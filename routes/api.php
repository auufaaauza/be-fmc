<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentScoreController;
use App\Http\Controllers\StudyProgramController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────────────────────────

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/schools', [SchoolController::class, 'publicList']);
Route::get('/classes/public', [SchoolClassController::class, 'publicList']);
Route::get('/schools/{schoolId}/classes', [SchoolClassController::class, 'publicSchoolClasses']);

// ── Authenticated ─────────────────────────────────────────────────────────────

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Common reference data
    Route::get('/subjects', [SubjectController::class, 'index']);
    Route::get('/interest-categories', [QuestionnaireController::class, 'categories']);
    Route::get('/study-programs/{id}', [StudyProgramController::class, 'show']);

    // ── Student ───────────────────────────────────────────────────────────────
    Route::middleware('role:student')->group(function () {
        Route::get('/my-scores', [StudentScoreController::class, 'myScores']);
        Route::post('/my-scores', [StudentScoreController::class, 'saveScores']);
        Route::get('/questionnaire', [QuestionnaireController::class, 'index']);
        Route::post('/questionnaire/answers', [QuestionnaireController::class, 'saveAnswers']);
        Route::post('/recommend', [RecommendationController::class, 'calculate']);
        Route::get('/my-recommendations', [RecommendationController::class, 'myHistory']);
        Route::get('/my-recommendations/latest', [RecommendationController::class, 'latest']);
    });

    // ── Admin (Guru BK) ───────────────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        // Student CRUD
        Route::get('/admin/students', [AdminController::class, 'listStudents']);
        Route::post('/admin/students', [AdminController::class, 'createStudent']);
        Route::put('/admin/students/{id}', [AdminController::class, 'updateStudent']);
        Route::delete('/admin/students/{id}', [AdminController::class, 'deleteStudent']);
        Route::post('/admin/students/{id}/reset-password', [AdminController::class, 'resetPassword']);
        Route::get('/admin/students/{id}/recommendation', [AdminController::class, 'studentRecommendation']);
        Route::post('/admin/students/{id}/counselor-notes', [AdminController::class, 'saveCounselorNote']);

        // Export / Import
        Route::get('/admin/students/export', [AdminController::class, 'exportStudents']);
        Route::get('/admin/students/export-report', [AdminController::class, 'exportFullReport']);
        Route::post('/admin/students/import', [AdminController::class, 'importStudents']);
        Route::get('/admin/students/import-template', [AdminController::class, 'downloadImportTemplate']);

        // Classes CRUD
        Route::get('/admin/classes', [SchoolClassController::class, 'index']);
        Route::post('/admin/classes', [SchoolClassController::class, 'store']);
        Route::put('/admin/classes/{id}', [SchoolClassController::class, 'update']);
        Route::delete('/admin/classes/{id}', [SchoolClassController::class, 'destroy']);

        // Study Programs
        Route::get('/admin/study-programs', [StudyProgramController::class, 'index']);
        Route::post('/admin/study-programs', [StudyProgramController::class, 'store']);
        Route::put('/admin/study-programs/{id}', [StudyProgramController::class, 'update']);

        // Stats & Dashboard
        Route::get('/admin/stats', [AdminController::class, 'stats']);
    });
});
