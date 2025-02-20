<?php

use App\Console\Commands\CheckPuptFacultySchedules;
use App\Http\Controllers\AccountSettings;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\StudentAssessmentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Middleware\AuthCheck;
use App\Http\Middleware\ClearSession;
use App\Http\Middleware\IdleTimeout;
use App\Http\Middleware\VerifyApiKey;
use Illuminate\Support\Facades\Route;



Route::middleware(ClearSession::class)->group(function () {
    Route::get('/', function () {
        return view('landing');
    });
});


Route::prefix('faculty')->group(function () {
    Route::get('forgot-password', [AccountSettings::class, 'forgotPasswordPageFaculty'])->name('faculty.send-email-pass');
    Route::middleware(ClearSession::class)->group(function () {
        Route::get('/', function () {
            return view('faculty-login');
        })->name('faculty.login');
    });
    Route::get('reset-password/{token}', [PasswordController::class, 'setNewPasswordPage'])->name('set-new-pass');
    Route::post('reset-password/{token}', [PasswordController::class, 'resetPassword'])->name('reset-password');
    Route::post('send-pass-link', [PasswordController::class, 'sendResetLinkFaculty'])->name('faculty.send-link-email');

    Route::get('change-temp-password', [PasswordController::class, 'changeTemporaryPasswordPage'])
        ->name('faculty.change-temp-pass');

    Route::post('reset-temp-password', [PasswordController::class, 'changeTemporaryPassword'])->name('reset-password');

    Route::middleware([AuthCheck::class])->group(function () {
        Route::get('class-record', [FacultyController::class, 'facultyClassRecordPage'])->name('faculty.class-record');
        Route::get('create-class-record', [FacultyController::class, 'facultyCreateClassRecord'])->name('faculty.create-class-record');
        Route::get('update-class-record/{classRecordID}', [FacultyController::class, 'facultyUpdateClassRecord'])->name('faculty.update-class-record');
        Route::get('course-list', [FacultyController::class, 'displayCourseList'])->name('faculty.course-list');
        Route::get('program-list', [FacultyController::class, 'displayProgramList'])->name('faculty.program-list');
        Route::get('feedback', [FacultyController::class, 'feedbackStudentPage'])->name('faculty.feedback');
        Route::prefix('reports')->group(function () {
            Route::get('submitted', [FacultyController::class, 'displaySubmittedReports'])->name('faculty.submitted-report');
            Route::get('verified', [FacultyController::class, 'displayVerifiedReports'])->name('faculty.verified-report');
        });

        Route::prefix('settings')->group(function () {
            Route::get('account', [FacultyController::class, 'displayAccountInfo'])->name('faculty.acc-info');
            Route::get('password', [FacultyController::class, 'displayUpdatePassword'])->name('faculty.update-pass');
            Route::get('activity-log', [FacultyController::class, 'displayFacultyActivityLog'])->name('faculty.act-logs');
            Route::get('archived-records', [FacultyController::class, 'displayFacultyArchivedClassRecord'])->name('faculty.archived-records');
            Route::post('update-password', [FacultyController::class, 'updatePassword'])->name('faculty.update-password');
            Route::post('update-personalInfo', [FacultyController::class, 'updatePersonalInfo'])->name('faculty.update-personalInfo');
        });

        Route::prefix('class-record')->group(function () {
            Route::get('student-info', [FacultyController::class, 'showClassRecordStudentInfo'])->name('faculty.view-class-record-stud-info');
            Route::get('semester-grade', [FacultyController::class, 'showClassRecordSemesterGrade'])->name('faculty.view-class-record-semester-grade');
            Route::get('faculty-class-record-individual-reports/{studentID}', [FacultyController::class, 'showIndividualReport'])
                ->name('faculty-class-record-individual-reports');

            Route::prefix('{gradingDistributionType}')->group(function () {
                Route::get('/', [FacultyController::class, 'showGradingDistribution'])->name('faculty.grading-distribution');
                Route::get('grades', [FacultyController::class, 'showClassRecordGrades'])
                    ->name('faculty.view-class-record-stud-grade');
                Route::get('{assessmentType}', [FacultyController::class, 'handleAssessmentType'])
                    ->name('faculty.view-class-record-info');
                Route::get('{assessmentType}/details', [FacultyController::class, 'showAssessmentDetails'])
                    ->name('faculty.view-class-record-stud-info-details');
            });
        });
    });
});


Route::prefix('admin')->group(function () {
    Route::middleware(ClearSession::class)->group(function () {
        Route::get('/', function () {
            return view('admin-login');
        })->name('admin.login');
    });

    Route::get('forgot-password', [AccountSettings::class, 'forgotPasswordPageAdmin'])->name('admin.send-email-pass');
    Route::get('reset-password/{token}', [PasswordController::class, 'setNewPasswordPage'])->name('set-new-pass');
    Route::post('reset-password/{token}', [PasswordController::class, 'resetPassword'])->name('reset-password');
    Route::post('send-pass-link', [PasswordController::class, 'sendResetLinkAdmin'])->name('admin.send-link-email');

    Route::get('change-temp-password', [PasswordController::class, 'changeTemporaryPasswordPage'])
        ->name('admin.change-temp-pass');

    Route::post('reset-temp-password', [PasswordController::class, 'changeTemporaryPassword'])->name('reset-password');

    Route::middleware([AuthCheck::class])->group(function () {
        Route::get('dashboard', [AdminController::class, 'adminDashboardPage'])->name('admin.dashboard');
        Route::get('accounts', [AdminController::class, 'adminAccountsPage'])->name('admin.accounts');
        Route::get('course-list', [AdminController::class, 'displayAdminCourseList'])->name('admin.course-list');
        Route::get('program-list', [AdminController::class, 'displayAdminProgramList'])->name('admin.program-list');
        Route::get('activity-log', [AdminController::class, 'displayAdminActivityLog'])->name('admin.act-logs');

        Route::get('admin-faculty-loads-page', [AdminController::class, 'displayFacultyLoads'])->name('admin.faculty-loads-page');


        Route::prefix('reports')->group(function () {
            Route::get('class-record', [AdminController::class, 'showClassRecordReports'])->name('admin.class-record-report');
            Route::prefix('to-verify')->group(function () {
                Route::get('/', [AdminController::class, 'showToVerifyReports'])->name('admin.to-verify-report');
                Route::get('view-file', [AdminController::class, 'viewSubmittedFile'])->name('admin.view-to-verify-report');
            });
            Route::get('verified', [AdminController::class, 'showVerifiedReports'])->name('admin.verified-report');
        });
        Route::get('org-chart', [AdminController::class, 'adminOrgChartPage'])->name('admin.org-chart');
        Route::prefix('settings')->group(function () {
            Route::get('account', [AdminController::class, 'displayAccountInfo'])->name('admin.acc-info');
            Route::get('password', [AdminController::class, 'displayUpdatePassword'])->name('admin.update-pass');
            Route::get('class-record-yearSem', [AdminController::class, 'classRecordYearSem'])->name('admin.class-record-yearSem');
            Route::post('update-password', [AdminController::class, 'updatePassword'])->name('admin.update-password');
            Route::post('update-personalInfo', [AdminController::class, 'updatePersonalInfo'])->name('faculty.update-personalInfo');
        });
    });
});




Route::prefix('student')->group(function () {
    Route::middleware(ClearSession::class)->group(function () {
        Route::get('/', function () {
            return view('student-login');
        })->name('student.login');
    });

    Route::get('forgot-password', [AccountSettings::class, 'forgotPasswordPageStudent'])->name('student.send-email-pass');
    Route::get('reset-password/{token}', [PasswordController::class, 'setNewPasswordPage'])->name('set-new-pass');
    Route::post('reset-password/{token}', [PasswordController::class, 'resetPassword'])->name('reset-password');
    Route::post('send-pass-link', [PasswordController::class, 'sendResetLinkStudent'])->name('student.send-link-email');

    Route::get('change-temp-password', [PasswordController::class, 'changeTemporaryPasswordPage'])
        ->name('student.change-temp-pass');

    Route::post('reset-temp-password', [PasswordController::class, 'changeTemporaryPassword'])->name('reset-password');

    Route::middleware([AuthCheck::class])->group(function () {
        Route::get('dashboard', [StudentController::class, 'studentDashboard'])->name('student.dashboard');
        Route::get('st/{classRecordID}/{GradingType}/{selectedAssessIDs}', [StudentController::class, 'storeStudentClassRecordIdGmail'])->name('student.store-class-record-id-email');
        Route::prefix('class-record')->group(function () {
            Route::get('{gradingDistributionType}', [StudentController::class, 'studentClassRecordPageInfo'])
                ->name('student.class-record-info');
            Route::get('{gradingDistributionType}/details', [StudentController::class, 'studentClassRecordPageAssessmentDetails'])
                ->name('student.class-record-assessment-details');
        });
        Route::prefix('settings')->group(function () {
            Route::get('account', [StudentController::class, 'displayAccountInfo'])->name('student.acc-info');
            Route::get('password', [StudentController::class, 'displayUpdatePassword'])->name('student.update-pass');
            Route::get('archived-records', [StudentController::class, 'displayStudentArchivedClassRecord'])->name('student.archived-records');
            Route::get('activity-log', [StudentController::class, 'displayFacultyActivityLog'])->name('student.act-logs');
            Route::post('update-password', [StudentController::class, 'updatePassword'])->name('student.update-password');
            Route::post('update-personalInfo', [StudentController::class, 'updatePersonalInfo'])->name('faculty.update-personalInfo');
        });
    });
});

Route::prefix('superadmin')->group(function () {
    Route::middleware([AuthCheck::class])->group(function () {
        Route::get('dashboard', [SuperAdminController::class, 'adminDashboardPage'])->name('super.dashboard');
        Route::get('accounts', [SuperAdminController::class, 'superAdminAccountsPage'])->name('super.accounts');
        Route::post('admin-update', [SuperAdminController::class, 'updateAdmin'])->name('admin.update');
        Route::get('course-list', [SuperAdminController::class, 'displaySuperAdminCourseList'])->name('super.course-list');
        Route::get('program-list', [SuperAdminController::class, 'displaySuperAdminProgramList'])->name('super.program-list');
        Route::get('branch-list', [SuperAdminController::class, 'displaySuperAdminBranchList'])->name('super.branch-list');
        Route::get('activity-log', [SuperAdminController::class, 'displaySuperAdminActivityLog'])->name('super.act-logs');
        Route::prefix('reports')->group(function () {
            Route::get('class-record', [SuperAdminController::class, 'showClassRecordReports'])->name('super.class-record-report');
        });
        Route::prefix('settings')->group(function () {
            Route::get('account', [SuperAdminController::class, 'displayAccountInfo'])->name('super.acc-info');
            Route::get('password', [SuperAdminController::class, 'displayUpdatePassword'])->name('super.update-pass');
            Route::get('class-record-yearSem', [SuperAdminController::class, 'classRecordYearSem'])->name('super.class-record-yearSem');
            Route::post('update-password', [SuperAdminController::class, 'updatePassword'])->name('super.update-password');
            Route::post('update-personalInfo', [SuperAdminController::class, 'updatePersonalInfo'])->name('super.update-personalInfo');
        });
    });
});

Route::fallback(function () {
    abort(404);
});

//GET DATATABLE JSON
Route::middleware([VerifyApiKey::class])->group(function () {
    //SuperAdmin
    Route::get('/get-admin-acc', [SuperAdminController::class, 'getAdminAccData']);
    Route::get('/get-branches-sa', [SuperAdminController::class, 'getBranchesData']);
    Route::get('/get-super-act-logs', [SuperAdminController::class, 'getActLogsData']);

    //Admin
    Route::get('/get-faculty-acc', [AdminController::class, 'getFacultyAccData']);
    Route::get('/get-admin-program', [AdminController::class, 'getProgramData']);
    Route::get('/get-admin-course', [AdminController::class, 'getCourseData']);
    Route::get('/get-admin-act-logs', [AdminController::class, 'getActLogsData']);
    Route::get('/get-admin-classrec-reports', [AdminController::class, 'getClassRecordReports']);
    Route::get('/get-faculty-schedules', [AdminController::class, 'getFacultySchedules']);

    //Faculty
    Route::get('/get-stud-info', [FacultyController::class, 'getStudentInfoData']);
    Route::get('/get-assessment-info', [FacultyController::class, 'getAssessmentInfoData']);
    Route::get('/get-assessment-details-info', [FacultyController::class, 'getAssessmentDetailsData']);
    Route::get('/get-faculty-act-logs', [FacultyController::class, 'getActLogsData']);
    Route::get('/get-faculty-archived', [FacultyController::class, 'getFacultyArchives']);
    Route::get('/fetch-pupt-faculty-schedules', [FacultyController::class, 'fetchPuptFacultySchedules']);

    //Student
    Route::get('/get-student-act-logs', [StudentController::class, 'getActLogsData']);
    Route::get('/get-student-archived', [StudentController::class, 'getStudentArchives']);

    //Notifications
    Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('fac.notifs');

    Route::get('/get-role', function () {
        return response()->json(['roleNum' => session('role')]);
    });
});


/*Admin 
        routes*/
Route::get('/get-school-year', [AdminController::class, 'getSchoolYear']);
Route::post('/update-school-year-semester', [AdminController::class, 'updateSchoolYearAndSemester'])->name('admin.updateSchoolYearSemester');
Route::post('/notify-professor', [AdminController::class, 'notifyProfessor'])->name('admin.notify-prof');
Route::post('/update-file/{fileID}', [AdminController::class, 'modifyAndSaveFile'])->name('update-file');
Route::post('/save-prof-info', [AdminController::class, 'addProfessor'])->name('save-prof-info');
Route::put('/professor-update', [AdminController::class, 'updateProfessor'])->name('professor.update');
Route::post('/send-faculty-credentials', [AdminController::class, 'sendFacultyCredentials'])->name('send.faculty-credentials');
Route::post('/send-faculty-credentials-batch', [AdminController::class, 'sendBatchProfessorCredentials'])->name('send.faculty-credentials-batch');
Route::post('/import-professor', [AdminController::class, 'importProfessor'])->name('import.professor');
Route::post('/import-programs', [AdminController::class, 'importProgram'])->name('import.program');
Route::post('/import-courses', [AdminController::class, 'importCourses'])->name('import.program');
Route::get('/get-class-records-dashboard/{professorId}', [AdminController::class, 'getClassRecordsDashboard']);
Route::get('/get-class-records-by-course-and-semester', [AdminController::class, 'getClassRecordsByCourseAndSemester']);
Route::get('/get-class-records-by-course-semester-school-year', [AdminController::class, 'getClassRecordsByCourseSemesterSchoolYear']);
Route::post('/save-program-info', [AdminController::class, 'addProgram'])->name('add.program');
Route::post('/save-course-info', [AdminController::class, 'addCourse'])->name('add.course');
Route::put('/update-program', [AdminController::class, 'updateProgram'])->name('program.update');
Route::put('/update-course', [AdminController::class, 'updateCourse'])->name('course.update');
Route::post('/store-esignature-admin', [AdminController::class, 'storeEsignature'])->name('store.esignature');
Route::post('/store-report-id', [AdminController::class, 'storeReportId'])->name('admin.store-report-id');
Route::post('/store-file-id-notif', [AdminController::class, 'storeReportIdNotif'])->name('admin.store-report-id-notif');
Route::get('/download-file/{id}', [AdminController::class, 'downloadFile'])->name('download.file');
Route::get('admin-faculty-loads', [FacultyController::class, 'adminFacultyLoads'])->name('admin.faculty.loads');

Route::post('/send-notice', [AdminController::class, 'sendNoticeToAdmin'])->name('send-notice');

Route::post('/test-send-notice', [AdminController::class, 'sendTestNotification']);

Route::post('/send-account-email', [AdminController::class, 'sendAccountEmail'])->name('admin.send-account-email');

Route::get('/download-individual-report/{studentID}', [FacultyController::class, 'generateIndividualReport'])->name('download.report');


/*Super Admin 
        routes*/
Route::post('/save-admin-info', [SuperAdminController::class, 'addAdmin'])->name('save-admin-info');
Route::post('/send-admin-credentials', [SuperAdminController::class, 'sendAdminCredentials'])->name('send.admin-credentials');
Route::post('/send-admin-credentials-batch', [SuperAdminController::class, 'sendBatchAdminCredentials'])->name('send.admin-credentials-batch');
Route::post('/save-branch-info', [SuperAdminController::class, 'addBranch'])->name('add.branch');
Route::put('/update-branch', [SuperAdminController::class, 'updateBranch'])->name('branch.update');

/*Faculty
        routes*/
Route::post('/store-term', [FacultyController::class, 'storeTermInSession']);
Route::get('/get-distribution-type', function () {
    return response()->json([
        'gradingTerm' => session('gradingTerm'),
        'selectedTab' => session('selectedTab')
    ]);
});
Route::get('/get-assessment-type', function () {
    return response()->json([
        'assessmentType' => session('assessmentType'),
        'selectedTab' => session('selectedTab')
    ]);
});
Route::post('/feedback/mark-as-read', [FacultyController::class, 'markAsRead'])->name('feedback.mark-as-read');
Route::post('/feedback/delete', [FacultyController::class, 'deleteFeedback'])->name('feedback.delete');
Route::get('/faculty/check-assessment-term-new/{assessmentType}', [FacultyController::class, 'checkAssessmentTermNew']);
Route::post('/archive-record', [FacultyController::class, 'archiveClassRecord'])->name('class-record.archive');
Route::post('/redirect-to-lists', [FacultyController::class, 'redirectToLists'])->name('faculty.redirect-to-list');
Route::post('/redirect-to-grades', [FacultyController::class, 'redirectToGrades'])->name('faculty.redirect-to-grades');
Route::post('/send-student-credentials', [FacultyController::class, 'sendStudentCredentials'])->name('send.faculty-credentials');
Route::post('/send-student-credentials-batch', [FacultyController::class, 'sendBatchStudentCredentials'])->name('send.student-credentials-batch');
Route::post('/validate-stud-email', [FacultyController::class, 'checkEmailStudentClassRecord'])->name('send.validate-email');
Route::post('/validate-stud-number', [FacultyController::class, 'checkStudentNumberClassRecord'])->name('send.validate-studnum');
Route::get('/faculty/get-assessment-types/{termType}', [FacultyController::class, 'getAssessmentTypes']);
Route::get('/faculty/get-alternative-assessments/{termType}', [FacultyController::class, 'getAlternativeAssessments']);
Route::post('/store-esignature-faculty', [FacultyController::class, 'storeEsignature'])->name('store.esignature');
Route::post('/generate-submit-grade', [FacultyController::class, 'generateAndSubmitGradesPDF'])->name('submit-grades.pdf');
Route::post('/generate-submit-grade-excel', [FacultyController::class, 'generateAndSubmitGradesExcel'])->name('submit-grades.excel');
Route::get('/faculty/get-term/{assessmentType}', [FacultyController::class, 'getTermForAssessmentType']);
Route::post('/store-class-record-id', [FacultyController::class, 'storeClassRecordId'])->name('faculty.store-class-record-id');
Route::post('/store-class-record-id-notice', [FacultyController::class, 'storeClassRecordIdNotice'])->name('faculty.store-class-record-id-notice');
Route::post('/update-stud-info', [FacultyController::class, 'updateStudent'])->name('update-stud-info');
Route::post('/save-stud-info', [FacultyController::class, 'addStudent'])->name('save-stud-info');
Route::post('/import-students', [FacultyController::class, 'importStudent'])->name('import.students');
Route::get('/get-class-records-data', [FacultyController::class, 'facultyClassRecordData'])->name('faculty.get-class-records');
Route::get('/get-program/{branchID}', [FacultyController::class, 'getPrograms']);
Route::get('/get-courses/{programID}', [FacultyController::class, 'getCourses']);
Route::get('/get-branches', [FacultyController::class, 'getBranches']);
Route::get('/filter-class-records', [FacultyController::class, 'filterByProgram']);
Route::post('/get-class-record', [FacultyController::class, 'getClassRecord']);
Route::post('/insert-classrecord', [FacultyController::class, 'storeClassrecord'])->name('insert-classrecord');
Route::post('/store-classrecord-integration', [FacultyController::class, 'storeClassrecordIntegration'])->name('store-classrecord-integration');
// Route::post('/publish-grades', [FacultyController::class, 'publishGrades'])->name('publish.grades');
Route::post('/toggle-grades', [FacultyController::class, 'toggleGrades'])->name('grades.toggle');



Route::get('/get-class-record-grading/{classRecordID}', [FacultyController::class, 'getClassRecordGrading']);
Route::get('/get-grading-distribution/{classRecordID}', [FacultyController::class, 'getGradingDistribution']);
Route::get('/get-schedule/{classRecordID}', [FacultyController::class, 'getSchedule']);
Route::put('/update-class-record/{classRecordID}', [FacultyController::class, 'updateClassRecord']);
Route::get('/class-records/{classRecordID}/midterm-grades', [FacultyController::class, 'showMidtermGrades'])->name('midterm.grades');
Route::get('/class-record/midterm-grades-pdf', [FacultyController::class, 'generatePDF'])->name('midterm-grades.pdf');
Route::get('/class-record/final-grades-pdf', [FacultyController::class, 'generateFinalPDF'])->name('final-grades.pdf');
Route::get('/class-record/semestral-grades-pdf', [FacultyController::class, 'generateSemestralPDF'])->name('semestral-grades.pdf');
Route::get('/export-semester-grade', [FacultyController::class, 'exportSemesterGradeToExcel'])->name('export.semester.grade');
Route::post('/update-grading-percentages', [FacultyController::class, 'updatePercentages']);

/*Student
        routes*/
Route::post('/store-feedback', [StudentController::class, 'storeFeedback']);
Route::post('/store-assessment-id-student', [StudentController::class, 'storeAssessmentIDStudent']);
Route::post('/redirect-to-lists-assessment-stud', [StudentController::class, 'redirectToLists'])->name('student.redirect-to-list');
Route::post('/store-stud-class-record-id', [StudentController::class, 'storeStudentClassRecordId'])->name('student.store-class-record-id');
Route::get('/export-student-assessments', [StudentController::class, 'exportStudentAssessments'])->name('export.student.assessments');
Route::post('/store-stud-class-record-id-notif', [StudentController::class, 'storeStudentClassRecordIdNotif'])->name('student.store-class-record-id-notif');

/*Notification
        routes*/
Route::get('/notifications/stream', [NotificationController::class, 'streamNotifications'])->name('stream');
Route::post('/send-notification', [NotificationController::class, 'sendNotificationScoresToView'])->name('send.notification');
Route::post('/notify-students-scores-batch', [NotificationController::class, 'sendNotificationViewableScoreDetailsBatch'])->name('publish.scores-batch');
Route::post('/notify-students-scores-individual', [NotificationController::class, 'sendNotificationViewableScoreDetailsIndividual'])->name('publish.scores-individual');
Route::post('/notify-students-batch', [NotificationController::class, 'sendNotificationViewableScoreBatch'])->name('publish.scores-batch');
Route::post('/notify-students-publish', [NotificationController::class, 'sendNotificationViewableScore'])->name('publish.scores');
Route::post('/mark-as-read', [NotificationController::class, 'markAsReadStoreClassRecordId'])->name('notif.markasread');
Route::post('/verified-files', [NotificationController::class, 'markAsReadNavigateToVerifiedFiles'])->name('notif.markasread-file');
Route::post('/notice-class-record/{classRecordID}', [NotificationController::class, 'markAsReadNavigateToClassRecord'])->name('notif.notice-submit');
Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);


/*Assessment 
        routes*/
Route::get('/get-assessments/{classRecordID}/{term}', [AssessmentController::class, 'getAssessments'])->name('faculty.get-assessments');
Route::post('/store-assessment-type', [AssessmentController::class, 'storeAssessmentType']);
Route::post('/store-distribution-type', [AssessmentController::class, 'storeDistributionType']);
Route::post('/store-assessment-id', [AssessmentController::class, 'storeAssessmentID']);
Route::post('/update-assessment-status', [AssessmentController::class, 'updateStatus']);
Route::post('/insert-assessment-info', [AssessmentController::class, 'storeAssessmentInfo'])->name('assessment.store-info');
Route::post('/insert-assessment-midterms', [AssessmentController::class, 'storeAssessmentMidterms'])->name('assessment.store-midterms');
Route::post('/insert-assessment-finals', [AssessmentController::class, 'storeAssessmentFinals'])->name('assessment.store-finals');
Route::post('/export-assessment-template', [AssessmentController::class, 'exportTemplate'])->name('export.assessment.template');
Route::post('/import-assessment', [AssessmentController::class, 'importAssessment'])->name('import.assessment.template');
Route::put('/update-assessment-midterms', [AssessmentController::class, 'updateAssessmentMidterms'])->name('assessment.update-midterms');
Route::post('/duplicate-assessment-midterms', [AssessmentController::class, 'duplicateAssessmentMidterms'])->name('assessment.duplicate-midterms');
Route::put('/update-assessment-finals', [AssessmentController::class, 'updateAssessmentFinals'])->name('assessment.update-finals');

/*Student Assessment 
        routes*/
Route::post('/store-scores', [StudentAssessmentController::class, 'saveScores']);
Route::post('/save-remarks', [StudentAssessmentController::class, 'saveRemarks']);
Route::post('/store-score-attendance', [StudentAssessmentController::class, 'saveScoreAttendance'])->name('assessment.scores');

/*Account Settings 
        routes*/
Route::post('/clear-session', [AccountSettings::class, 'clearSession']);

/*Login Route */

Route::middleware(['web'])->group(function () {
    Route::post('/sign-in-faculty', [LoginController::class, 'faculty_authenticate']);
    Route::post('/sign-in-admin', [LoginController::class, 'admin_authenticate']);
    Route::post('/sign-in-student', [LoginController::class, 'student_authenticate']);
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});
