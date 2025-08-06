<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::with("owners")->paginate(10);
        return response()->json([
            "success" => true,
            "data" => $companies
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "description" => "required|string",
            "location" => "required|string|max:255",
            "website" => "nullable|url|max:255",
            "industry" => "nullable|string|max:255",
            "size" => "nullable|string|max:255",
            "founded_year" => "nullable|integer|min:1800|max:" . date("Y"),
            "logo_url" => "nullable|url|max:255",
            "social_links" => "nullable|array",
        ]);

        $company = Company::create($request->all());

        Auth::user()->ownedCompanies()->attach($company->id, ["is_primary" => true]);

        return response()->json([
            "success" => true,
            "message" => "Company created successfully.",
            "data" => $company
        ], 201);
    }

    public function show(Company $company)
    {
        $company->load("owners", "jobs");
        return response()->json([
            "success" => true,
            "data" => $company
        ]);
    }

    public function update(Request $request, Company $company)
    {
 
        if (!Auth::user()->ownedCompanies->contains($company->id)) {
            return response()->json([
                "success" => false,
                "message" => "You are not authorized to update this company."
            ], 403);
        }

        $request->validate([
            "name" => "required|string|max:255",
            "description" => "required|string",
            "location" => "required|string|max:255",
            "website" => "nullable|url|max:255",
            "industry" => "nullable|string|max:255",
            "size" => "nullable|string|max:255",
            "founded_year" => "nullable|integer|min:1800|max:" . date("Y"),
            "logo_url" => "nullable|url|max:255",
            "social_links" => "nullable|array",
        ]);

        $company->update($request->all());

        return response()->json([
            "success" => true,
            "message" => "Company updated successfully.",
            "data" => $company
        ]);
    }

    public function destroy(Company $company)
    {
        if (!$company->owners()->where("user_id", Auth::id())->wherePivot("is_primary", true)->exists()) {
            return response()->json([
                "success" => false,
                "message" => "You are not authorized to delete this company."
            ], 403);
        }

        $company->delete();

        return response()->json([
            "success" => true,
            "message" => "Company deleted successfully."
        ]);
    }

    public function myCompanies()
    {
        $companies = Auth::user()->ownedCompanies()->with("jobs")->paginate(10);
        return response()->json([
            "success" => true,
            "data" => $companies
        ]);
    }

    public function addOwner(Request $request, Company $company)
    {
        if (!Auth::user()->ownedCompanies->contains($company->id)) {
            return response()->json(['success' => false, 'message' => 'Not authorized.'], 403);
        }
        $request->validate(['user_id' => 'required|exists:users,id']);
        $company->owners()->syncWithoutDetaching([$request->user_id]);
        return response()->json(['success' => true, 'message' => 'Owner added.']);
    }

    public function addOwners(Request $request, Company $company)
    {
        // Check if current user is an owner of the company (primary or secondary)
        if (!$company->owners()->where("user_id", Auth::id())->exists()) {
            return response()->json([
                "success" => false,
                "message" => "You are not authorized to add owners to this company."
            ], 403);
        }

        $request->validate([
            "user_id" => "required|exists:users,id",
            "email" => "sometimes|email|exists:users,email", // Alternative way to identify user
        ]);

        // Get user ID from email if provided instead of user_id
        $userId = $request->user_id;
        if ($request->has('email') && !$request->has('user_id')) {
            $user = User::where('email', $request->email)->first();
            $userId = $user->id;
        }

        // Check if user is already an owner
        if ($company->owners()->where("user_id", $userId)->exists()) {
            return response()->json([
                "success" => false,
                "message" => "User is already an owner of this company."
            ], 400);
        }

        // Add user as a secondary owner (not primary)
        $company->owners()->attach($userId, ["is_primary" => false]);

        // Load the user to return their info
        $user = User::find($userId);

        return response()->json([
            "success" => true,
            "message" => "User '{$user->name}' has been added as a company owner successfully.",
            "data" => [
                "company" => $company->name,
                "new_owner" => [
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "is_primary" => false
                ]
            ]
        ]);
    }

}
