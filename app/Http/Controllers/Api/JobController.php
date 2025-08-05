<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::with('company')
            ->active()
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $jobs
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'employment_type' => 'required|in:full-time,part-time,contract,internship',
            'experience_level' => 'nullable|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gt:salary_min',
            'salary_currency' => 'nullable|string|max:3',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'expires_at' => 'nullable|date|after_or_equal:today',
            'is_active' => 'boolean',
        ]);

        $company = Company::findOrFail($validatedData['company_id']);
        if (!Auth::user()->ownedCompanies->contains($company->id)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to post jobs for this company.'
            ], 403);
        }

        $validatedData['posted_by'] = Auth::id();
        $job = Job::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Job created successfully.',
            'data' => $job->load('company')
        ], 201);
    }

    public function show(Job $job)
    {
        $job->load('company', 'postedBy');
        return response()->json([
            'success' => true,
            'data' => $job
        ]);
    }

    public function update(Request $request, Job $job)
    {
        if (!Auth::user()->ownedCompanies->contains($job->company_id)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this job.'
            ], 403);
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'employment_type' => 'required|in:full-time,part-time,contract,internship',
            'experience_level' => 'nullable|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gt:salary_min',
            'salary_currency' => 'nullable|string|max:3',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'expires_at' => 'nullable|date|after_or_equal:today',
            'is_active' => 'boolean',
        ]);

        $job->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Job updated successfully.',
            'data' => $job->load('company')
        ]);
    }

    public function destroy(Job $job)
    {
        if (!Auth::user()->ownedCompanies->contains($job->company_id)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this job.'
            ], 403);
        }

        $job->delete();

        return response()->json([
            'success' => true,
            'message' => 'Job deleted successfully.'
        ]);
    }
}