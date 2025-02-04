<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <body class="dashboard">
        <table class="mx-auto">
            <thead>
                <tr class="border border-dark text-center">
                    <th>S.No</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>URL</th>
                    <th>Technology</th>
                    <th>Service Type</th>
                    <th>Portfolio Status</th>
                    <th>Actions</th>
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
                        <td class="border border-dark px-4">
                            <span class="light-indicators" style="color: {{ $portfolio->status === 'Active' ? '#188518' : '#bd1919' }}; font-size: 50px;">&#x2022;</span>
                            <!-- {{ $portfolio->status }} -->
                        </td>
                        <td class="border border-dark px-4">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.portfolio.edit', $portfolio->id) }}" class="btn btn-warning">Edit</a> |
                            
                            <!-- Delete Button -->
                            <form action="{{ route('admin.portfolio.delete', $portfolio->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this portfolio?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>

</x-app-layout>
