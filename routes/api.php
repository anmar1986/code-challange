<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\SearchController;



// Public routes
Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);

// Public Job Routes (anyone can view jobs)
Route::get("/jobs", [JobController::class, "index"]);
Route::get("/jobs/{job}", [JobController::class, "show"]);

// Search Route (public)
Route::get("/search", [SearchController::class, "search"]);

// Protected routes
Route::middleware("auth:sanctum")->group(function () {
    Route::post("/logout", [AuthController::class, "logout"]);
    Route::get("/user", [AuthController::class, "user"]);

    // Company Routes
    Route::apiResource("companies", CompanyController::class);
    Route::get("company/{company}", [CompanyController::class, "show"]);
    Route::get("/my-companies", [CompanyController::class, "myCompanies"]);
    Route::post("/company/{company}/add-owner", [CompanyController::class, "addOwners"]);


    // Job Routes (only authenticated users can create, update, delete)
    Route::post("/jobs", [JobController::class, "store"]);
    Route::put("/jobs/{job}", [JobController::class, "update"]);
    Route::delete("/jobs/{job}", [JobController::class, "destroy"]);
});
