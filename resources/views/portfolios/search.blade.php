<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Search</title>
    <style>
        .light-indicators{
            font-size: 50px;
        }
        .text-green {
            color: #188518;
            font-weight: bold; /* Optional */
        }

        .text-red {
            color: #bd1919;
            font-weight: bold; /* Optional */
        }
    </style>
</head> -->
<x-app-layout>
<!-- <body class=""> -->
<section class="max-w-6xl mx-auto mt-4" style="padding-bottom: 40px;">
    <h1 class="mt-4 mb-4">Portfolio Search Results</h1>

    <table>
        <thead>
            <tr class="border border-dark text-center">
                <th>S.No</th>
                <th>Title</th>
                <th>Description</th>
                <th>URL</th>
                <th>Technology</th>
                <th>Service Type</th>
                <th>Portfolio Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($portfolios as $index => $portfolio)
                <tr class="border border-dark text-center">
                    <td class="border border-dark px-4">{{ $index + 1 }}</td>
                    <td class="border border-dark px-4">{{ $portfolio->title }}</td>
                    <td class="border border-dark px-4">{{ $portfolio->description }}</td>
                    <td class="border border-dark px-4">
                        <a href="{{ strpos($portfolio->url, 'http') === 0 ? $portfolio->url : 'http://' . $portfolio->url }}" target="_blank">
                            {{ $portfolio->url }}
                        </a>
                    </td>
                    <td class="border border-dark px-4">{{ $portfolio->technology }}</td>
                    <td class="border border-dark px-4">{{ $portfolio->service_type }}</td>
                    <td class="border border-dark px-4"><span class="light-indicators" style="color: {{ $portfolio->status === 'Active' ? '#188518' : '#bd1919' }}; font-size: 40px;">&#x2022;</span>
                            <!-- {{ $portfolio->status }} -->
                    </td>    
                </tr>
            @endforeach
        </tbody>
    </table>
</section>
<!-- </body> -->
</x-app-layout>  
<!-- </html> -->
