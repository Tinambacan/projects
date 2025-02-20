<?php

use App\Http\Controllers\ChatBotController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [ChatBotController::class, "LandingPage"])->name('landingpage');

Route::get('/chatbot', [ChatBotController::class, "ChatPage"])->name('chatbot');

Route::get('/about', [ChatBotController::class, "AboutPage"])->name('about');

Route::get('/contact', [ChatBotController::class, "ContactPage"])->name('contact');

Route::post('/send-question', [ChatBotController::class, 'sendChat']);
// Route::post('/send-theme', [ChatBotController::class, 'theme']);

Route::get('/csrf-token', function () {
    return response()->json(['csrfToken' => csrf_token()]);
});

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/chatbot', function () {
//         return Inertia::render('Chatbot');
//     })->name('chatbot');
// });
