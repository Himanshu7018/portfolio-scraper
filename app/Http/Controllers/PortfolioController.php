<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Portfolio;
use App\Helpers\UrlHelper;

class PortfolioController extends Controller
{
    /**
     * Common method to fetch portfolios based on filters and assign URL status.
     */
    protected function fetchPortfolios(Request $request)
    {
        $query     = $request->input('query', '');
        $technology = $request->input('technology', '');
        $serviceType = $request->input('service_type', '');

        $portfoliosQuery = Portfolio::query();

        if (!empty($query)) {
            $portfoliosQuery->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('url', 'LIKE', "%{$query}%");
            });
        }
        if (!empty($technology)) {
            $portfoliosQuery->where('technology', $technology);
        }
        if (!empty($serviceType)) {
            $portfoliosQuery->where('service_type', $serviceType);
        }

        $portfolios = $portfoliosQuery->get();

        // Batch-check URL statuses using UrlHelper
        $urls = $portfolios->pluck('url')->toArray();
        $statuses = UrlHelper::checkUrls($urls);

        // Map statuses back to portfolios safely
        foreach ($portfolios as $portfolio) {
            $portfolio->status = (isset($statuses[$portfolio->url]) && $statuses[$portfolio->url]) ? 'Active' : 'Inactive';
        }

        return $portfolios;
    }

    /**
     * Search Page: Display filtered portfolios.
     */
    public function search(Request $request)
    {
        $portfolios = $this->fetchPortfolios($request);

        // Retrieve unique values for dropdown filters
        $technologies = Portfolio::select('technology')->distinct()->pluck('technology');
        $serviceTypes = Portfolio::select('service_type')->distinct()->pluck('service_type');

        return view('portfolios.search', compact('portfolios', 'technologies', 'serviceTypes'));
    }

    /**
     * Dashboard: Display filtered portfolios with extra actions (edit, delete).
     */
    public function dashboard(Request $request)
    {
        $portfolios = $this->fetchPortfolios($request);

        // Retrieve unique values for dropdown filters (for admin use)
        $technologies = Portfolio::select('technology')->distinct()->pluck('technology');
        $serviceTypes = Portfolio::select('service_type')->distinct()->pluck('service_type');

        return view('dashboard', compact('portfolios', 'technologies', 'serviceTypes'));
    }

    // Function to check if a URL is active
    // private function isLinkActive($url)
    // {
    //     $headers = @get_headers($url);
    //     return $headers && strpos($headers[0], '200') !== false;
    // }
    public function isLinkActive($url)
    {
        $headers = @get_headers($url);
        return $headers && strpos($headers[0], '200') !== false;
    }

    public function create()
    {
        return view('admin.add_portfolio');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'url' => 'nullable|url',
            'technology' => 'required|string|max:255',
            'service_type' => 'required|string|max:255',
        ]);

        Portfolio::create($validatedData);

        return redirect()->route('admin.portfolio.add')->with('success', 'Portfolio added successfully!');
    }

    public function edit($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        return view('admin.portfolio.edit', compact('portfolio'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'nullable|url',
            'technology' => 'nullable|string',
            'service_type' => 'nullable|string',
            // 'status' => 'required|string|in:Active,Inactive',
        ]);

        $portfolio = Portfolio::findOrFail($id);
        $portfolio->update($request->all());

        return redirect()->route('dashboard')->with('success', 'Portfolio updated successfully!');
    }

    public function destroy($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $portfolio->delete();

        return redirect()->route('dashboard')->with('success', 'Portfolio deleted successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('portfolio_ids', []);
        if (!empty($ids)) {
            Portfolio::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Selected portfolios deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'No portfolios selected.']);
    }
    
}
