<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard view
    public function showDashboard()
    {
        return view('admin.dashboard');
    }

    // Handle bulk upload CSV
    public function uploadCSV(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $filePath = $file->getRealPath();

        $data = array_map('str_getcsv', file($filePath));
        $header = array_map('trim', $data[0]);
        unset($data[0]);

        foreach ($data as $row) {
            $rowData = array_combine($header, $row);

            Portfolio::create([
                'title' => $rowData['title'] ?? 'No Title',
                'description' => $rowData['description'] ?? 'No Description',
                'url' => $rowData['url'] ?? null,
                'technology' => $rowData['technology'] ?? null,
                'service_type' => $rowData['service_type'] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'CSV file uploaded successfully!');
    }

    public function addPortfolio(Request $request)
    {
        Portfolio::create($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'Portfolio added successfully!');
    }

}

