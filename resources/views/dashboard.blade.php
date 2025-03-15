<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-white">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="mt-5 overflow-auto">
        <!-- Filter Form -->
        <form id="filterForm" method="GET" action="{{ route('dashboard') }}">
            <div class="d-flex gap-3 justify-content-center">
                <div class="col-md-3">
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
                <div class="col-md-2 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary px-4">Search</button>
                    <button type="button" class="btn btn-secondary px-4" id="clearFilters">Clear</button>
                </div>
            </div>
        </form>

        <div class="d-flex justify-content-end gap-3 py-4">
            <button type="button" class="btn btn-success color-white" id="bulkCopy">Bulk Copy</button>
            <button type="button" class="btn btn-danger" id="bulkDelete">Bulk Delete</button>
        </div>


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
                    <th>Select</th>
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
                            <a href="{{ route('admin.portfolio.edit', $portfolio->id) }}" class="btn btn-warning my-1">Edit</a> |
                            <form action="{{ route('admin.portfolio.delete', $portfolio->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger my-1" onclick="return confirm('Are you sure you want to delete this portfolio?');">Delete</button>
                            </form>
                        </td>
                        <td>                       
                            <input class="form-check-input border border-dark" type="checkbox" value="{{ $portfolio->id }}" id="flexCheckDefault">
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
            window.location.href = "{{ route('dashboard') }}";
        });

        filterTable();
    });
    </script>
</x-app-layout>
