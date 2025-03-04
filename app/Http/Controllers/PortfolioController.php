<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Portfolio;
use App\Helpers\UrlHelper;

class PortfolioController extends Controller
{
    // public function search(Request $request)
    // {
    //     // Get the search query
    //     $query = $request->input('query');

    //     // Fetch portfolios based on the query, or all if no query provided
    //     $portfolios = Portfolio::where('title', 'LIKE', "%$query%")
    //         ->orWhere('description', 'LIKE', "%$query%")
    //         ->orWhere('url', 'LIKE', "%$query%")
    //         ->get();

    //     // Check if URLs are active or inactive
    //     // foreach ($portfolios as $portfolio) {
    //     //     $portfolio->status = $this->isLinkActive($portfolio->url) ? 'Active' : 'Inactive';
    //     // }
    //     foreach ($portfolios as $portfolio) {
    //         $portfolio->status = UrlHelper::isLinkActive($portfolio->url) ? 'Active' : 'Inactive';
    //     }

    //     return view('portfolios.search', compact('portfolios', 'query'));
    // }

    /**
     * Display the filtered search results.
     */
    public function search(Request $request)
    {
        // Retrieve the search term and filter inputs.
        $searchTerm = $request->input('query');
        $technology = $request->input('technology');
        $serviceType = $request->input('service_type');

        // Start building the query.
        $portfolios = Portfolio::query();

        // Group the text field conditions to ensure proper AND combination.
        if ($searchTerm) {
            $portfolios->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                ->orWhere('url', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Apply the technology filter if provided.
        if ($technology) {
            $portfolios->where('technology', $technology);
        }

        // Apply the service type filter if provided.
        if ($serviceType) {
            $portfolios->where('service_type', $serviceType);
        }

        // Execute the query.
        $portfolios = $portfolios->get();

        foreach ($portfolios as $portfolio) {
            $portfolio->status = UrlHelper::isLinkActive($portfolio->url) ? 'Active' : 'Inactive';
        }

        // Fetch unique technology and service types for dropdowns.
        $technologies = Portfolio::select('technology')->distinct()->pluck('technology');
        $serviceTypes = Portfolio::select('service_type')->distinct()->pluck('service_type');

        return view('portfolios.search', compact('portfolios', 'searchTerm', 'technologies', 'serviceTypes'));
    }


    /**
     * Display the dashboard with filtered results.
     */
    public function dashboard(Request $request)
    {
        $query = $request->input('query');
        $technology = $request->input('technology');
        $serviceType = $request->input('service_type');

        $portfolios = Portfolio::query();

        if ($query) {
            $portfolios->where('title', 'LIKE', "%$query%")
                ->orWhere('description', 'LIKE', "%$query%")
                ->orWhere('url', 'LIKE', "%$query%");
        }

        if ($technology) {
            $portfolios->where('technology', $technology);
        }

        if ($serviceType) {
            $portfolios->where('service_type', $serviceType);
        }

        $portfolios = $portfolios->get();

        // Fetch unique technology and service types for dropdowns
        $technologies = Portfolio::select('technology')->distinct()->pluck('technology');
        $serviceTypes = Portfolio::select('service_type')->distinct()->pluck('service_type');

        return view('dashboard', compact('portfolios', 'query', 'technologies', 'serviceTypes'));
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
            'status' => 'required|string|in:Active,Inactive',
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


}
