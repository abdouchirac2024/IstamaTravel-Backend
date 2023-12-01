<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\TrajetController;
use App\Http\Controllers\Api\ChauffeurController;
use App\Http\Controllers\Api\BusController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\AgentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route pour obtenir les informations de l'utilisateur actuellement authentifié
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
   return $request->user();
});

// Obtenir la liste de tous les utilisateurs
Route::middleware('auth:sanctum')->get('/users', [AuthController::class, 'index']);

// Routes du contrôleur AuthController avec le préfixe 'auth'
Route::prefix('auth')->group(function () {
   Route::post('/login', [AuthController::class, 'login']);
   Route::post('/register', [AuthController::class, 'register']);
   Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
   Route::post('/reset-password', [AuthController::class, 'reset']);
   Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

   // Google and Facebook authentication routes
   Route::get('/login/google', [AuthController::class, 'redirectToGoogle']);
   Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback']);
   Route::get('/login/facebook', [AuthController::class, 'redirectToFacebook']);
   Route::get('/login/facebook/callback', [AuthController::class, 'handleFacebookCallback']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    // Routes du contrôleur StudentController
    Route::apiResource('students', StudentController::class);
    Route::post('trajets/search', [TrajetController::class, 'search']);
    Route::resource('chauffeurs', ChauffeurController::class);
    Route::resource('buses', BusController::class);
    Route::get('buses/trashed', [BusController::class, 'indexTrashed']);
    Route::get('buses/not-trashed', [BusController::class, 'indexNotTrashed']);
});

Route::apiResource('roles', RoleController::class);
Route::apiResource('trajets', TrajetController::class);
Route::apiResource('agents', AgentController::class);

