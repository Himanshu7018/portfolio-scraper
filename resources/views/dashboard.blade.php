<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        <!-- Filter Form -->
        <form id="filterForm" method="GET" action="{{ route('dashboard') }}">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" id="query" name="query" value="{{ request('query') }}" class="form-control" placeholder="Search by title, description, or URL">
                </div>
                <div class="col-md-3">
                    <select id="technology" name="technology" class="form-control">
                        <option value="">Select Technology</option>
                        @foreach ($technologies as $tech)
                            <option value="{{ $tech }}" {{ request('technology') == $tech ? 'selected' : '' }}>{{ $tech }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="service_type" name="service_type" class="form-control">
                        <option value="">Select Service Type</option>
                        @foreach ($serviceTypes as $service)
                            <option value="{{ $service }}" {{ request('service_type') == $service ? 'selected' : '' }}>{{ $service }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <button type="button" class="btn btn-secondary" id="clearFilters">Clear</button>
                </div>
            </div>
        </form>

        <br>

        <!-- Portfolio Table -->
        <table class="table table-bordered" id="portfolioTable">
            <thead>
                <tr class="text-center">
                    <th>S.No</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>URL</th>
                    <th>Technology</th>
                    <th>Service Type</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($portfolios as $index => $portfolio)
                    <tr class="text-center align-middle">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $portfolio->title }}</td>
                        <td>{{ $portfolio->description }}</td>
                        <td>
                            <a href="{{ strpos($portfolio->url, 'http') === 0 ? $portfolio->url : 'http://' . $portfolio->url }}" target="_blank">
                                {{ $portfolio->url }}
                            </a>
                        </td>
                        <td>{{ $portfolio->technology }}</td>
                        <td>{{ $portfolio->service_type }}</td>
                        <td>
                            <!-- {{ $portfolio->status }} -->
                            <span class="light-indicators" style="color: {{ $portfolio->status === 'Active' ? '#188518' : '#bd1919' }}; font-size: 40px;">&#x2022;</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.portfolio.edit', $portfolio->id) }}" class="btn btn-warning">Edit</a> |
                            <form action="{{ route('admin.portfolio.delete', $portfolio->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this portfolio?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Client-Side Filtering Script (optional, for instant feedback) -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const queryInput = document.getElementById("query");
        const technologyDropdown = document.getElementById("technology");
        const serviceTypeDropdown = document.getElementById("service_type");
        const portfolioTable = document.getElementById("portfolioTable");
        const clearFiltersBtn = document.getElementById("clearFilters");

        function filterTable() {
            const query = queryInput.value.toLowerCase().trim();
            const tech = technologyDropdown.value;
            const service = serviceTypeDropdown.value;

            let rows = portfolioTable.getElementsByTagName("tr");

            for (let row of rows) {
                const title = row.querySelector("td:nth-child(2)")?.textContent.toLowerCase().trim() || "";
                const rowTech = row.querySelector("td:nth-child(5)")?.textContent.trim() || "";
                const rowService = row.querySelector("td:nth-child(6)")?.textContent.trim() || "";

                const matchesQuery = query === "" || title.includes(query);
                const matchesTech = tech === "" || rowTech === tech;
                const matchesService = service === "" || rowService === service;

                row.style.display = (matchesQuery && matchesTech && matchesService) ? "" : "none";
            }
        }

        queryInput.addEventListener("input", filterTable);
        technologyDropdown.addEventListener("change", filterTable);
        serviceTypeDropdown.addEventListener("change", filterTable);

        clearFiltersBtn.addEventListener("click", function() {
            queryInput.value = "";
            technologyDropdown.value = "";
            serviceTypeDropdown.value = "";
            filterTable();
        });

        filterTable();
    });
    </script>
</x-app-layout>
