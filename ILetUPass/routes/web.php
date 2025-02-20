<?php

use App\Http\Controllers\AudioMusicController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ILetYouPassController;
use App\Http\Controllers\LoginController;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('user-selection');
});

//for test email
Route::get('/test', function () {
    return view('testemail');
});
Route::post('/test-email', [EmailController::class, 'test']);

Route::post('/sent-email', [EmailController::class, 'subscribe']);

Route::post('/sent-prof-email', [EmailController::class, 'profSubscribe']);

Route::post('/change-pass', [IletYouPassController::class, 'studentChangePass']);

Route::post('/change-prof-pass', [IletYouPassController::class, 'profChangePass']);

Route::post('/change-new-pass', [IletYouPassController::class, 'studentChangeNewPass']);
Route::post('/forgot-pass', [EmailController::class, 'studentForgotPass']);

Route::get('/reload-captcha', [EmailController::class, 'reloadCaptcha']);


Route::get('/dataTable', function () {
    return view('dataTable');
});
Route::get('/student-change-pass', function () {
    return view('passwords.student-change-pass');
})->name('student-change-pass');
Route::get('/student-activate-pass', function () {
    return view('passwords.student-activate-pass');
})->name('student-activate-pass');

Route::get('/prof-activate-pass', function () {
    return view('passwords.professor-activate-pass');
})->name('prof-activate-pass');

Route::get('/disabled-student-acc', function () {
    return view('disabled-acc');
});
Route::get('/prof-signup', function (){
    return view ('prof-signup');
});

Route::get('/student-forgot-pass', [ILetYouPassController::class, 'ForgotPassStudent']);

Route::get('/student-login', [ILetYouPassController::class, 'LoginStudent'])->middleware('sessionClear');

Route::get('/admin-login', [ILetYouPassController::class, 'LoginAdmin'])->middleware('sessionClear');

Route::get('/prof-login', [ILetYouPassController::class, 'LoginProf'])->middleware('sessionClear');

Route::get('/ILetYouPass', [ILetYouPassController::class, 'ILetYouPass'])->middleware('authCheckUser');

Route::get('/display-game', [ILetYouPassController::class, 'DisplayGame'])->middleware('authCheckUser');

Route::get('/display-start', [ILetYouPassController::class, 'DisplayStart']);

Route::get('/display-difficulty/{myValue}', [ILetYouPassController::class, 'DisplayDifficulty']);

Route::get('/display-subjects', [ILetYouPassController::class, 'DisplaySubjects']);


Route::get('/display-acc', [ILetYouPassController::class, 'DisplayAccSelection']);

Route::get('/stud-acc', [ILetYouPassController::class, 'DisplayStudentAcc']);

Route::get('/prof-acc', [ILetYouPassController::class, 'DisplayProfAcc']);

Route::get('/display-manage-ques-answ/{myValue}', [ILetYouPassController::class, 'DisplayManageQuesAns']);

Route::post('/sub-difficulty', [ILetYouPassController::class, 'getDifficulty']);

Route::get('/select-difficulty', [ILetYouPassController::class, 'getDifficulty']);

Route::get('/pass-question', 'App\Http\Controllers\ILetYouPassController@passIndex')->name('question.passIndex');

Route::post('/insert-score', [ILetYouPassController::class, 'SaveScore']);

Route::get('/display-score-record', [ILetYouPassController::class, 'ScoreRecord']);

Route::get('/sign-up', [ILetYouPassController::class, 'SignUp']);

Route::post('/save-stud-info', [ILetYouPassController::class, 'SaveInfoStudent']);

Route::post('/save-prof-info', [ILetYouPassController::class, 'SaveInfoProf']);

Route::post('/save-subject', [ILetYouPassController::class, 'saveSubject']);

Route::post('/update-subject', [ILetYouPassController::class, 'updateSubject']);

Route::post('/delete-subject', [ILetYouPassController::class, 'deleteSubject']);

Route::post('/stud-import', [ILetYouPassController::class, 'studentImport']);

Route::post('/prof-import', [ILetYouPassController::class, 'profImport']);

Route::post('/stud-disable', [ILetYouPassController::class, 'deactivateStudent']);

Route::post('/stud-enable', [ILetYouPassController::class, 'reactivateStudent']);

Route::post('/stud-delete-batch', [ILetYouPassController::class, 'deleteBatchStudent']);

Route::post('/prof-disable', [ILetYouPassController::class, 'deactivateProf']);

Route::post('/prof-enable', [ILetYouPassController::class, 'reactivateProf']);

Route::post('/prof-delete-batch', [ILetYouPassController::class, 'deleteBatchProf']);

Route::post('/update-student', [ILetYouPassController::class, 'updateStudentInfo']);

Route::post('/update-prof', [ILetYouPassController::class, 'updateProfInfo']);

Route::post('/delete-student/{stud_id}', [ILetYouPassController::class, 'deleteStudent']);

Route::post('/delete-prof/{prof_id}', [ILetYouPassController::class, 'deleteProf']);

Route::post('/filter-subject', [ILetYouPassController::class, 'filterSubject'])->name('filter-subject');

Route::post('/filter-levels', [ILetYouPassController::class, 'filterLevels'])->name('filter-levels');

Route::post('/add-question-answer/{myValue}', [ILetYouPassController::class, 'addQuestion']);

Route::post('/edit-question-answer', [ILetYouPassController::class, 'updateQuestion']);

Route::post('/delete-question-answer', [ILetYouPassController::class, 'deleteQuestion']);

Route::get('/search', [ILetYouPassController::class, 'DisplayStudentInfo'])->name('student.search');

Route::post('/question-import/{myValue}', [ILetYouPassController::class, 'questionImport']);

// Route::get('', [ILetYouPassController::class, 'questionImport']);

Route::post('/search',  [ILetYouPassController::class, 'search'])->name('student.search.submit');



Route::middleware(['web'])->group(function () {

    Route::post('/sign-in', [LoginController::class, 'login_authenticate']);
    Route::post('/sign-in-admin', [LoginController::class, 'admin_authenticate']);
    Route::post('/sign-in-profs', [LoginController::class, 'prof_authenticate']);
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});
