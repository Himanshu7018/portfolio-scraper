<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Portfolio Search</title>
    <!-- <style>
        
    </style> -->
</head>
<body>
<section class="container-xl mt-4" style="padding-bottom: 40px;">
    <h1 class="my-5 text-center">Portfolio Search Results</h1>
    <!-- Filter Form -->
    <form class="text-center mx-5" id="searchForm" method="GET" action="{{ route('portfolios.search') }}">
        <div class="row d-flex">
            <div class="col-md-4">
                <input type="text" id="query" name="query" value="{{ request('query') }}" class="form-control" placeholder="Search by Title, URL, or Description">
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
    </div>

    <br>

    <table class="mx-auto">
        <thead>
            <tr class="border border-dark text-center">
                <th class="py-2 px-3">S.No</th>
                <th class="py-2 px-3">Title</th>
                <th class="py-2 px-3">Description</th>
                <th class="py-2 px-3">URL</th>
                <th class="py-2 px-3">Technology</th>
                <th class="py-2 px-3">Service Type</th>
                <th class="py-2 px-3">Portfolio Status</th>
                <th class="py-2 px-3">Select</th>
            </tr>
        </thead>
        <tbody class="border border-dark">
            @if ($portfolios->isEmpty())
                <tr class="border border-dark text-center">
                    <td colspan="7" class="py-2 px-3 text-center">No matching records found.</td>
                </tr>
            @else
                @foreach ($portfolios as $index => $portfolio)
                    <tr class="border border-dark text-center fw-medium">
                        <td class="border border-dark px-4 py-1">{{ $index + 1 }}</td>
                        <td class="border border-dark px-4 py-1 portfolio-title">{{ $portfolio->title }}</td>
                        <td class="border border-dark px-4 py-1 portfolio-description">{{ $portfolio->description }}</td>
                        <td class="border border-dark px-4 py-1">
                            <a class="text-dark text-decoration-none" href="{{ strpos($portfolio->url, 'http') === 0 ? $portfolio->url : 'http://' . $portfolio->url }}" target="_blank">
                                {{ $portfolio->url }}
                            </a>
                        </td>
                        <td class="border border-dark px-4 py-1 portfolio-tech">{{ $portfolio->technology }}</td>
                        <td class="border border-dark px-4 py-1 portfolio-service">{{ $portfolio->service_type }}</td>
                        <td class="border border-dark px-4 py-1">
                            <span class="light-indicators" style="color: {{ $portfolio->status === 'Active' ? '#188518' : '#bd1919' }}; font-size: 40px;">&#x2022;</span>
                        </td>  
                        <td>                       
                            <input class="form-check-input border border-dark" type="checkbox" value="{{ $portfolio->id }}" id="flexCheckDefault">
                        </td>  
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</section>

<footer>
    <div class="container-xl text-center mb-4 fw-bold">
        <p>© {{ date('Y') }} <a class="text-dark text-decoration-none" href="https://www.codexmattrix.com/">CodexMattrix</a>. All rights reserved.</p>
    </div>
</footer>

<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const queryInput = document.getElementById("query");
        const technologyDropdown = document.getElementById("technology");
        const serviceTypeDropdown = document.getElementById("service_type");
        const clearFiltersBtn = document.getElementById("clearFilters");
        const portfolioTable = document.querySelector("table tbody");
        
        function filterResults() {
            const searchText = queryInput.value.toLowerCase();
            const selectedTech = technologyDropdown.value;
            const selectedService = serviceTypeDropdown.value;
            const rows = portfolioTable.querySelectorAll("tr");
            let visibleCount = 0;

            rows.forEach(row => {
                if (row.classList.contains("border")) {
                    const title = row.querySelector(".portfolio-title")?.textContent.toLowerCase() || "";
                    const description = row.querySelector(".portfolio-description")?.textContent.toLowerCase() || "";
                    const tech = row.querySelector(".portfolio-tech")?.textContent || "";
                    const service = row.querySelector(".portfolio-service")?.textContent || "";

                    const titleMatch = searchText === "" || title.includes(searchText) || description.includes(searchText);
                    const techMatch = selectedTech === "" || tech === selectedTech;
                    const serviceMatch = selectedService === "" || service === selectedService;

                    if (titleMatch && techMatch && serviceMatch) {
                        row.style.display = "";
                        visibleCount++;
                    } else {
                        row.style.display = "none";
                    }
                }
            });

            // If no results, show "No matching records found"
            if (visibleCount === 0) {
                let noRecordRow = document.getElementById("noRecords");
                if (!noRecordRow) {
                    noRecordRow = document.createElement("tr");
                    noRecordRow.id = "noRecords";
                    noRecordRow.innerHTML = `<td colspan="7" class="py-2 px-3 text-center">No matching records found.</td>`;
                    portfolioTable.appendChild(noRecordRow);
                }
                noRecordRow.style.display = "";
            } else {
                const noRecordRow = document.getElementById("noRecords");
                if (noRecordRow) {
                    noRecordRow.style.display = "none";
                }
            }
        }

        queryInput.addEventListener("input", filterResults);
        technologyDropdown.addEventListener("change", filterResults);
        serviceTypeDropdown.addEventListener("change", filterResults);

        // Clear Filters & Reload Page
        clearFiltersBtn.addEventListener("click", function() {
            window.location.href = "{{ route('portfolios.search') }}";
        });

        filterResults();
    });
</script>
</body>
</html>
