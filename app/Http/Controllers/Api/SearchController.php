<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\SearchLog;

class SearchController extends Controller
{
    /**
     * Search for jobs based on various criteria
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        // Validate the search parameters
        $validated = $request->validate([
            'search_term' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|in:full-time,part-time,contract,internship',
            'experience_level' => 'nullable|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'filters' => 'nullable|array',
            'page' => 'nullable|integer|min:1',
        ]);

        // Start with the base query - only active jobs with company relationships
        $query = Job::with('company')->active();

        // Apply search term filter (search in title and description)
        if ($request->filled('search_term')) {
            $searchTerm = $request->search_term;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('requirements', 'like', '%' . $searchTerm . '%');
            });
        }

        // Apply location filter
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Apply employment type filter
        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }

        // Apply experience level filter
        if ($request->filled('experience_level')) {
            $query->where('experience_level', 'like', '%' . $request->experience_level . '%');
        }

        // Apply salary range filters
        if ($request->filled('salary_min')) {
            $query->where(function ($q) use ($request) {
                $q->where('salary_min', '>=', $request->salary_min)
                  ->orWhere('salary_max', '>=', $request->salary_min);
            });
        }

        if ($request->filled('salary_max')) {
            $query->where(function ($q) use ($request) {
                $q->where('salary_max', '<=', $request->salary_max)
                  ->orWhere('salary_min', '<=', $request->salary_max);
            });
        }

        // Apply additional filters if provided
        if ($request->filled('filters') && is_array($request->filters)) {
            foreach ($request->filters as $key => $value) {
                if (!empty($value)) {
                    switch ($key) {
                        case 'company_size':
                            $query->whereHas('company', function ($q) use ($value) {
                                $q->where('size', $value);
                            });
                            break;
                        case 'industry':
                            $query->whereHas('company', function ($q) use ($value) {
                                $q->where('industry', 'like', '%' . $value . '%');
                            });
                            break;
                        case 'remote_work':
                            if ($value === 'true' || $value === true) {
                                $query->where('location', 'like', '%remote%');
                            }
                            break;
                    }
                }
            }
        }

        // Order by latest jobs first and paginate
        $results = $query->latest('created_at')->paginate(10);

        // Log the search for analytics
        try {
            SearchLog::create([
                'search_term' => $request->search_term,
                'location' => $request->location,
                'employment_type' => $request->employment_type,
                'experience_level' => $request->experience_level,
                'salary_min' => $request->salary_min,
                'salary_max' => $request->salary_max,
                'filters' => $request->filters ? json_encode($request->filters) : null,
                'results_count' => $results->total(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'session_id' => session()->getId(),
                'user_id' => optional($request->user())->id,
            ]);
        } catch (\Exception $e) {
            // Log the error but don't fail the search
            \Log::error('Failed to log search: ' . $e->getMessage());
        }

        // Return the search results in a consistent format
        return response()->json([
            'success' => true,
            'message' => 'Search completed successfully',
            'data' => $results,
            'search_params' => [
                'search_term' => $request->search_term,
                'location' => $request->location,
                'employment_type' => $request->employment_type,
                'experience_level' => $request->experience_level,
                'salary_min' => $request->salary_min,
                'salary_max' => $request->salary_max,
                'filters' => $request->filters,
            ]
        ]);
    }

    /**
     * Get popular search terms for autocomplete
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPopularSearches()
    {
        $popularSearches = SearchLog::select('search_term')
            ->whereNotNull('search_term')
            ->where('search_term', '!=', '')
            ->groupBy('search_term')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(10)
            ->pluck('search_term');

        return response()->json([
            'success' => true,
            'data' => $popularSearches
        ]);
    }

    /**
     * Get search suggestions based on partial input
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSuggestions(Request $request)
    {
        $request->validate([
            'term' => 'required|string|min:2|max:100'
        ]);

        $term = $request->term;

        // Get job title suggestions
        $jobTitles = Job::select('title')
            ->where('title', 'like', '%' . $term . '%')
            ->where('is_active', true)
            ->distinct()
            ->limit(5)
            ->pluck('title');

        // Get location suggestions
        $locations = Job::select('location')
            ->where('location', 'like', '%' . $term . '%')
            ->where('is_active', true)
            ->distinct()
            ->limit(5)
            ->pluck('location');

        return response()->json([
            'success' => true,
            'data' => [
                'job_titles' => $jobTitles,
                'locations' => $locations
            ]
        ]);
    }
}



