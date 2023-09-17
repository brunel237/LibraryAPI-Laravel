<?php

use App\Http\Controllers\OuvrageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix("users/{path}/")->group(function() {
//     Route::post("/credit", [AccountController::class, 'credit']);
//     Route::post("/discredit", [AccountController::class, 'discredit']);
    Route::post("/freeze/{?account_id}", [AccountController::class, 'freeze'])->whereAlphaNumeric('account_id');
    Route::post("/block/{?account_id}", [AccountController::class, 'block'])->whereAlphaNumeric('account_id');
    Route::post("/activate/{?account_id}", [AccountController::class, 'activate'])->whereAlphaNumeric('account_id');
});


Route::post("/signin", [AuthController::class, 'signin']);
Route::post("/signup", [AuthController::class, 'signup']);

Route::apiResource('books', BookController::class);
Route::apiResource('authors', AuthorController::class);
Route::apiResource('users', UserController::class);

Route::middleware('auth:api')->post("/payments/new", [UserController::class, 'newPayment']);
Route::middleware('auth:api')->post("/commands", [RequestController::class, 'newCommand']);

Route::middleware(['auth:api','admin'])->group(function() {
    Route::get("/commands/{id?}", [RequestController::class, 'getCommand']);
    Route::put("/commands/{id}", [RequestController::class, 'confirmCommand']);
    Route::delete("/commands/{id}", [RequestController::class, 'rejectCommand']);
});

// Route::post("/inscription", [AuthController::class, 'inscription'])->name("inscription");
// Route::get("/deconnexion", function (){

    // if (auth()->user()) {
        // Auth::logout();
        // return response()->json(["message"=>"Déconnexion Réussie..."], 200);
    // }
    // return response()->json(["message"=>"Vous n'êtes connecté..."], 422);
// });
// Route::post("/connexion", [AuthController::class, 'connexion'])->name("connexion");

// Route::prefix("ouvrages")->middleware('auth:api')->group(function (){
//     Route::get("/", [OuvrageController::class, 'index']);
//     Route::middleware('admin')->group(function(){
//         Route::post("/", [OuvrageController::class, 'store']);
//         Route::put("/{id}", [OuvrageController::class, 'update'])->whereNumber('id');;
//         Route::delete("/{id}", [OuvrageController::class, 'destroy'])->whereNumber('id');
//     });

//     Route::prefix("livres")->group(function(){
//         Route::get("/", [LivreController::class, 'index']);
//         Route::get("/exemplaires", [LivreController::class, 'getExemplaire']);
//         Route::post("/exemplaires", [LivreController::class, 'storeExemplaire']);
//         Route::middleware('admin')->group(function(){
//             Route::post("/", [LivreController::class, 'store']);
//             Route::put("/{id}", [LivreController::class, 'update'])->whereNumber('id');;
//             Route::delete("/{id}", [LivreController::class, 'destroy'])->whereNumber('id');
//         });
//     });

// });

// Route::group(["middleware" => ['auth:api']], function(){
//     Route::resource("utilisateurs", UserController::class);
//     Route::get("/statut", [UserController::class, 'userStatus']);
//     Route::resource("auteurs", AuteurController::class)->middleware('admin');

//     Route::prefix("prets")->group(function(){
//         Route::post("/nouveau", [PretController::class, 'demanderPret']);
//         Route::middleware('admin')->group(function(){
//             Route::post("/valider_demande/{id_user}", [PretController::class, 'validerDemandePret']);
//             Route::post("/{id_user}", [PretController::class, 'show']);
//             Route::get("/", [PretController::class, 'index']);
//             Route::put("/{id}", [PretController::class, 'update']);
//         });
//     });
// });

// Route::post("/auteur_livre", [AuteurController::class, 'storeAuteurLivre']);


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });




