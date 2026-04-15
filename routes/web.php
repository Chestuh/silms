<?php

use App\Http\Controllers\Admin\CompletionStatusController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Instructor\CoursesController as InstructorCoursesController;
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\Instructor\MaterialsController as InstructorMaterialsController;
use App\Http\Controllers\Instructor\MessagesController as InstructorMessagesController;
use App\Http\Controllers\Instructor\ProgressController as InstructorProgressController;
use App\Http\Controllers\Instructor\RubricsController as InstructorRubricsController;
use App\Http\Controllers\Student\AcademicStatusController;
use App\Http\Controllers\Student\AdmissionController;
use App\Http\Controllers\Student\CredentialsController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\DisciplinaryController;
use App\Http\Controllers\Student\FeesController;
use App\Http\Controllers\Student\GwaController;
use App\Http\Controllers\Student\HonorsController;
use App\Http\Controllers\Student\LearningController;
use App\Http\Controllers\Student\MessagesController;
use App\Http\Controllers\Student\PerformanceController;
use App\Http\Controllers\Student\ProgressController;
use App\Http\Controllers\Student\RateMaterialController;
use App\Http\Controllers\Student\RemindersController;
use App\Http\Controllers\Student\SelfAssessmentController;
use App\Http\Controllers\Student\TransferRequestsController as StudentTransferRequestsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('gwa', [GwaController::class, 'index'])->name('gwa');
    Route::get('academic-status', [AcademicStatusController::class, 'index'])->name('academic-status');
    Route::get('admission', [AdmissionController::class, 'index'])->name('admission');
    Route::get('transfer-requests', [StudentTransferRequestsController::class, 'index'])->name('transfer-requests.index');
    Route::get('transfer-requests/create', [StudentTransferRequestsController::class, 'create'])->name('transfer-requests.create');
    Route::post('transfer-requests', [StudentTransferRequestsController::class, 'store'])->name('transfer-requests.store');
    Route::get('disciplinary', [DisciplinaryController::class, 'index'])->name('disciplinary');
    Route::get('honors', [HonorsController::class, 'index'])->name('honors');
    Route::get('fees', [FeesController::class, 'index'])->name('fees');
    Route::get('fees/{fee}/pay', [\App\Http\Controllers\Student\PaymentController::class, 'show'])->name('fees.pay');
    Route::post('fees/{fee}/pay', [\App\Http\Controllers\Student\PaymentController::class, 'store'])->name('fees.pay.store');
    Route::get('learning', [LearningController::class, 'index'])->name('learning.index');
    Route::get('learning/material/{material}', [LearningController::class, 'show'])->name('learning.material');
    Route::get('learning/rate/{material}', [RateMaterialController::class, 'edit'])->name('rate.edit');
    Route::post('learning/rate/{material}', [RateMaterialController::class, 'update'])->name('rate.update');
    Route::get('progress', [ProgressController::class, 'index'])->name('progress');
    Route::get('reminders', [RemindersController::class, 'index'])->name('reminders.index');
    Route::post('reminders', [RemindersController::class, 'store'])->name('reminders.store');
    Route::delete('reminders/{reminder}', [RemindersController::class, 'destroy'])->name('reminders.destroy');
    Route::get('messages', [MessagesController::class, 'index'])->name('messages');
    Route::get('messages/with/{user}', [MessagesController::class, 'thread'])->name('messages.thread');
    Route::get('messages/create', [MessagesController::class, 'create'])->name('messages.create');
    Route::post('messages', [MessagesController::class, 'store'])->name('messages.store');
    Route::get('performance', [PerformanceController::class, 'index'])->name('performance');
    Route::get('credentials', [CredentialsController::class, 'index'])->name('credentials.index');
    Route::post('credentials', [CredentialsController::class, 'store'])->name('credentials.store');
    Route::get('credentials/letter/{credential_request}', [CredentialsController::class, 'downloadLetter'])->name('credentials.letter.download');
    Route::get('self-assessment', [SelfAssessmentController::class, 'index'])->name('self-assessment');
    Route::get('learning-path', [\App\Http\Controllers\Student\LearningPathController::class, 'index'])->name('learning-path.index');
    Route::get('learning-aids', [\App\Http\Controllers\Student\AutoLearningAidsController::class, 'index'])->name('learning-aids.index');
    Route::get('learning-aids/{aid}/download', [\App\Http\Controllers\Student\AutoLearningAidsController::class, 'download'])->name('learning-aids.download');
    Route::get('dashboard-personalization', [\App\Http\Controllers\Student\DashboardPersonalizationController::class, 'index'])->name('dashboard-personalization');
    Route::put('dashboard-personalization', [\App\Http\Controllers\Student\DashboardPersonalizationController::class, 'update'])->name('dashboard-personalization.update');
});

Route::middleware(['auth', 'role:instructor'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');
    Route::get('courses', [InstructorCoursesController::class, 'index'])->name('courses.index');
    Route::get('materials', [InstructorMaterialsController::class, 'index'])->name('materials.index');
    Route::get('materials/create', [InstructorMaterialsController::class, 'create'])->name('materials.create');
    Route::post('materials', [InstructorMaterialsController::class, 'store'])->name('materials.store');
    Route::get('materials/{material}/edit', [InstructorMaterialsController::class, 'edit'])->name('materials.edit');
    Route::put('materials/{material}', [InstructorMaterialsController::class, 'update'])->name('materials.update');
    Route::delete('materials/{material}', [InstructorMaterialsController::class, 'destroy'])->name('materials.destroy');
    Route::post('materials/{material}/archive', [InstructorMaterialsController::class, 'archive'])->name('materials.archive');
    Route::post('materials/{material}/unarchive', [InstructorMaterialsController::class, 'unarchive'])->name('materials.unarchive');
    Route::get('learning-aids', [\App\Http\Controllers\Instructor\AutoLearningAidsController::class, 'index'])->name('learning-aids.index');
    Route::get('learning-aids/create', [\App\Http\Controllers\Instructor\AutoLearningAidsController::class, 'create'])->name('learning-aids.create');
    Route::post('learning-aids', [\App\Http\Controllers\Instructor\AutoLearningAidsController::class, 'store'])->name('learning-aids.store');
    Route::get('progress', [InstructorProgressController::class, 'index'])->name('progress.index');
    Route::get('messages', [InstructorMessagesController::class, 'index'])->name('messages.index');
    Route::get('messages/with/{user}', [InstructorMessagesController::class, 'thread'])->name('messages.thread');
    Route::get('messages/create', [InstructorMessagesController::class, 'create'])->name('messages.create');
    Route::post('messages', [InstructorMessagesController::class, 'store'])->name('messages.store');
    Route::get('rubrics', [InstructorRubricsController::class, 'index'])->name('rubrics.index');
    Route::get('rubrics/create', [InstructorRubricsController::class, 'create'])->name('rubrics.create');
    Route::post('rubrics', [InstructorRubricsController::class, 'store'])->name('rubrics.store');
    Route::get('rubrics/{rubric}', [InstructorRubricsController::class, 'show'])->name('rubrics.show');
    Route::get('rubrics/{rubric}/edit', [InstructorRubricsController::class, 'edit'])->name('rubrics.edit');
    Route::put('rubrics/{rubric}', [InstructorRubricsController::class, 'update'])->name('rubrics.update');
    Route::delete('rubrics/{rubric}', [InstructorRubricsController::class, 'destroy'])->name('rubrics.destroy');
    Route::get('grades', [\App\Http\Controllers\Instructor\GradesController::class, 'index'])->name('grades.index');
    Route::get('grades/course/{course}', [\App\Http\Controllers\Instructor\GradesController::class, 'show'])->name('grades.show');
    Route::put('grades/course/{course}', [\App\Http\Controllers\Instructor\GradesController::class, 'update'])->name('grades.update');
    Route::get('gwa', [\App\Http\Controllers\Instructor\GwaController::class, 'index'])->name('gwa.index');
    Route::get('academic-status', [\App\Http\Controllers\Instructor\AcademicStatusController::class, 'index'])->name('academic-status.index');
    Route::get('academic-load', [\App\Http\Controllers\Instructor\AcademicLoadController::class, 'index'])->name('academic-load.index');
    Route::get('honors', [\App\Http\Controllers\Instructor\HonorsController::class, 'index'])->name('honors.index');
    Route::get('skill-gaps', [\App\Http\Controllers\Instructor\SkillGapsController::class, 'index'])->name('skill-gaps.index');

    // No AI-specific routes. Student/instructor core features remain.
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/enrollment-data', [AdminDashboardController::class, 'liveEnrollmentData'])->name('dashboard.enrollment-data');
    Route::get('dashboard/materials-usage-data', [AdminDashboardController::class, 'liveMaterialsUsageData'])->name('dashboard.materials-usage-data');
    Route::get('dashboard/grade-distribution-data', [AdminDashboardController::class, 'liveGradeDistributionData'])->name('dashboard.grade-distribution-data');
    Route::get('dashboard/kpi-metrics', [AdminDashboardController::class, 'liveKpiMetrics'])->name('dashboard.kpi-metrics');
    // Pre-Registrations Management
    Route::get('pre-registrations', [\App\Http\Controllers\Admin\PreRegistrationsController::class, 'index'])->name('pre-registrations.index');
    Route::get('pre-registrations/{preRegistration}', [\App\Http\Controllers\Admin\PreRegistrationsController::class, 'show'])->name('pre-registrations.show');
    Route::post('pre-registrations/{preRegistration}/approve', [\App\Http\Controllers\Admin\PreRegistrationsController::class, 'approve'])->name('pre-registrations.approve');
    Route::post('pre-registrations/{preRegistration}/reject', [\App\Http\Controllers\Admin\PreRegistrationsController::class, 'reject'])->name('pre-registrations.reject');
    // Students Management
    Route::get('students', [\App\Http\Controllers\Admin\StudentsController::class, 'index'])->name('students.index');
    Route::get('students/{student}/edit', [\App\Http\Controllers\Admin\StudentsController::class, 'edit'])->name('students.edit');
    Route::put('students/{student}', [\App\Http\Controllers\Admin\StudentsController::class, 'update'])->name('students.update');
    Route::get('students/{student}', [\App\Http\Controllers\Admin\StudentsController::class, 'show'])->name('students.show');
    Route::get('instructors', [\App\Http\Controllers\Admin\InstructorsController::class, 'index'])->name('instructors.index');
    Route::get('instructors/create', [\App\Http\Controllers\Admin\InstructorsController::class, 'create'])->name('instructors.create');
    Route::post('instructors', [\App\Http\Controllers\Admin\InstructorsController::class, 'store'])->name('instructors.store');
    Route::get('instructors/{instructor}/edit', [\App\Http\Controllers\Admin\InstructorsController::class, 'edit'])->name('instructors.edit');
    Route::put('instructors/{instructor}', [\App\Http\Controllers\Admin\InstructorsController::class, 'update'])->name('instructors.update');
    Route::delete('instructors/{instructor}', [\App\Http\Controllers\Admin\InstructorsController::class, 'destroy'])->name('instructors.destroy');
    Route::resource('courses', \App\Http\Controllers\Admin\CoursesController::class)->except(['show']);
    Route::get('materials', [\App\Http\Controllers\Admin\MaterialsController::class, 'index'])->name('materials.index');
    Route::post('materials/{material}/approve', [\App\Http\Controllers\Admin\MaterialsController::class, 'approve'])->name('materials.approve');
    Route::post('materials/{material}/reject', [\App\Http\Controllers\Admin\MaterialsController::class, 'reject'])->name('materials.reject');
    Route::delete('materials/{material}', [\App\Http\Controllers\Admin\MaterialsController::class, 'destroy'])->name('materials.destroy');
    Route::get('enrollments', [\App\Http\Controllers\Admin\EnrollmentsController::class, 'index'])->name('enrollments.index');
    Route::get('enrollments/{enrollment}', [\App\Http\Controllers\Admin\EnrollmentsController::class, 'show'])->name('enrollments.show');
    Route::post('enrollments/{enrollment}/approve', [\App\Http\Controllers\Admin\EnrollmentsController::class, 'approve'])->name('enrollments.approve');
    Route::post('enrollments/{enrollment}/reject', [\App\Http\Controllers\Admin\EnrollmentsController::class, 'reject'])->name('enrollments.reject');
    Route::get('reports', [\App\Http\Controllers\Admin\ReportsController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [\App\Http\Controllers\Admin\ReportsController::class, 'export'])->name('reports.export');
    Route::get('credentials', [\App\Http\Controllers\Admin\CredentialRequestsController::class, 'index'])->name('credentials.index');
    Route::get('credentials/{credential_request}', [\App\Http\Controllers\Admin\CredentialRequestsController::class, 'show'])->name('credentials.show');
    Route::get('credentials/{credential_request}/letter', [\App\Http\Controllers\Admin\CredentialRequestsController::class, 'letter'])->name('credentials.letter');
    Route::post('credentials/{credential_request}/send', [\App\Http\Controllers\Admin\CredentialRequestsController::class, 'sendToStudent'])->name('credentials.send');
    Route::post('credentials/{credential_request}/upload', [\App\Http\Controllers\Admin\CredentialRequestsController::class, 'uploadSigned'])->name('credentials.upload');
    Route::post('credentials/signature', [\App\Http\Controllers\Admin\CredentialRequestsController::class, 'saveSignature'])->name('credentials.signature');
    Route::get('progress', [\App\Http\Controllers\Admin\ProgressController::class, 'index'])->name('progress.index');
    Route::get('learning-path', [\App\Http\Controllers\Admin\LearningPathController::class, 'index'])->name('learning-path.index');
    Route::get('learning-path/create', [\App\Http\Controllers\Admin\LearningPathController::class, 'create'])->name('learning-path.create');
    Route::post('learning-path', [\App\Http\Controllers\Admin\LearningPathController::class, 'store'])->name('learning-path.store');
    Route::get('learning-path/{learning_path_rule}/edit', [\App\Http\Controllers\Admin\LearningPathController::class, 'edit'])->name('learning-path.edit');
    Route::put('learning-path/{learning_path_rule}', [\App\Http\Controllers\Admin\LearningPathController::class, 'update'])->name('learning-path.update');
    Route::delete('learning-path/{learning_path_rule}', [\App\Http\Controllers\Admin\LearningPathController::class, 'destroy'])->name('learning-path.destroy');
    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    Route::get('academic-status', [\App\Http\Controllers\Admin\AcademicStatusController::class, 'index'])->name('academic-status.index');
    Route::get('admission', [\App\Http\Controllers\Admin\AdmissionController::class, 'index'])->name('admission.index');
    Route::get('admission/create', [\App\Http\Controllers\Admin\AdmissionController::class, 'create'])->name('admission.create');
    Route::post('admission', [\App\Http\Controllers\Admin\AdmissionController::class, 'store'])->name('admission.store');
    Route::get('disciplinary', [\App\Http\Controllers\Admin\DisciplinaryController::class, 'index'])->name('disciplinary.index');
    Route::get('disciplinary/create', [\App\Http\Controllers\Admin\DisciplinaryController::class, 'create'])->name('disciplinary.create');
    Route::post('disciplinary', [\App\Http\Controllers\Admin\DisciplinaryController::class, 'store'])->name('disciplinary.store');
    Route::get('disciplinary/{disciplinary}/edit', [\App\Http\Controllers\Admin\DisciplinaryController::class, 'edit'])->name('disciplinary.edit');
    Route::put('disciplinary/{disciplinary}', [\App\Http\Controllers\Admin\DisciplinaryController::class, 'update'])->name('disciplinary.update');
    Route::delete('disciplinary/{disciplinary}', [\App\Http\Controllers\Admin\DisciplinaryController::class, 'destroy'])->name('disciplinary.destroy');
    Route::get('academic-load', [\App\Http\Controllers\Admin\AcademicLoadController::class, 'index'])->name('academic-load.index');
    Route::post('materials/{material}/archive', [\App\Http\Controllers\Admin\MaterialsController::class, 'archive'])->name('materials.archive');
    Route::post('materials/{material}/unarchive', [\App\Http\Controllers\Admin\MaterialsController::class, 'unarchive'])->name('materials.unarchive');
    // Transfer requests management
    Route::get('transfer-requests', [\App\Http\Controllers\Admin\TransferRequestsController::class, 'index'])->name('transfer-requests.index');
    Route::get('transfer-requests/{transferRequest}', [\App\Http\Controllers\Admin\TransferRequestsController::class, 'show'])->name('transfer-requests.show');
    Route::post('transfer-requests/{transferRequest}/approve', [\App\Http\Controllers\Admin\TransferRequestsController::class, 'approve'])->name('transfer-requests.approve');
    Route::post('transfer-requests/{transferRequest}/reject', [\App\Http\Controllers\Admin\TransferRequestsController::class, 'reject'])->name('transfer-requests.reject');
    // Skill gaps report for admin
    Route::get('skill-gaps', [\App\Http\Controllers\Admin\SkillGapsController::class, 'index'])->name('skill-gaps.index');
    // Academic Completion Status
    Route::get('completion-status', [CompletionStatusController::class, 'index'])->name('completion-status.index');
    Route::get('completion-status/student/{student}', [CompletionStatusController::class, 'student'])->name('completion-status.student');
    Route::get('api/completion-status/student/{student}', [CompletionStatusController::class, 'apiStatus'])->name('api.completion-status.student');
});

Route::middleware(['auth', 'role:cashier'])->prefix('cashier')->name('cashier.')->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Cashier\DashboardController::class, 'index'])->name('dashboard');
    Route::get('fees', [\App\Http\Controllers\Cashier\FeesController::class, 'index'])->name('fees.index');
    Route::get('fees-create', [\App\Http\Controllers\Cashier\FeesController::class, 'create'])->name('fees.create');
    Route::post('fees', [\App\Http\Controllers\Cashier\FeesController::class, 'store'])->name('fees.store');
    Route::get('fees/{fee}', [\App\Http\Controllers\Cashier\FeesController::class, 'show'])->name('fees.show');
    Route::post('fees/{fee}/mark-paid', [\App\Http\Controllers\Cashier\FeesController::class, 'markPaid'])->name('fees.mark-paid');
    Route::post('fees/{fee}/verify-payment', [\App\Http\Controllers\Cashier\FeesController::class, 'verifyPayment'])->name('fees.verify-payment');
    Route::get('credentials', [\App\Http\Controllers\Cashier\CredentialsController::class, 'index'])->name('credentials.index');
    Route::post('credentials/{credential_request}/clear-payment', [\App\Http\Controllers\Cashier\CredentialsController::class, 'clearPayment'])->name('credentials.clear-payment');
    Route::get('admission', [\App\Http\Controllers\Cashier\AdmissionController::class, 'index'])->name('admission.index');
    Route::get('students', [\App\Http\Controllers\Cashier\StudentsController::class, 'index'])->name('students.index');
    Route::get('students/{student}', [\App\Http\Controllers\Cashier\StudentsController::class, 'show'])->name('students.show');
});
