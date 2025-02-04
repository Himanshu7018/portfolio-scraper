<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Portfolio;
use App\Helpers\UrlHelper;

class PortfolioController extends Controller
{
    public function search(Request $request)
    {
        // Get the search query
        $query = $request->input('query');

        // Fetch portfolios based on the query, or all if no query provided
        $portfolios = Portfolio::where('title', 'LIKE', "%$query%")
            ->orWhere('description', 'LIKE', "%$query%")
            ->orWhere('url', 'LIKE', "%$query%")
            ->get();

        // Check if URLs are active or inactive
        // foreach ($portfolios as $portfolio) {
        //     $portfolio->status = $this->isLinkActive($portfolio->url) ? 'Active' : 'Inactive';
        // }
        foreach ($portfolios as $portfolio) {
            $portfolio->status = UrlHelper::isLinkActive($portfolio->url) ? 'Active' : 'Inactive';
        }

        return view('portfolios.search', compact('portfolios', 'query'));
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
