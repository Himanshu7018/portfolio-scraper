document.addEventListener("DOMContentLoaded", function () {
    const bulkDeleteBtn = document.getElementById("bulkDelete");
    const bulkCopyBtn = document.getElementById("bulkCopy");

    function getSelectedIds() {
        return Array.from(document.querySelectorAll("input[type='checkbox']:checked"))
            .map(checkbox => checkbox.value);
    }

    // BULK DELETE FUNCTIONALITY
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener("click", function () {
            const selectedIds = getSelectedIds();
            if (selectedIds.length === 0) {
                alert("Please select at least one portfolio to delete.");
                return;
            }

            if (!confirm("Are you sure you want to delete the selected portfolios?")) {
                return;
            }

            fetch(bulkDeleteUrl, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ portfolio_ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert("Failed to delete portfolios. Please try again.");
                }
            })
            .catch(error => console.error("Error:", error));
        });
    }

    // BULK COPY FUNCTIONALITY (Only URLs)
    if (bulkCopyBtn) {
        bulkCopyBtn.addEventListener("click", function () {
            const selectedUrls = Array.from(document.querySelectorAll("input[type='checkbox']:checked"))
                .map(checkbox => {
                    const urlElement = checkbox.closest("tr").querySelector("td:nth-child(4) a");
                    return urlElement ? urlElement.href : null;
                })
                .filter(url => url); // Remove null values

            if (selectedUrls.length === 0) {
                alert("Please select at least one portfolio to copy URLs.");
                return;
            }

            // Copy URLs to clipboard
            navigator.clipboard.writeText(selectedUrls.join("\n"))
                .then(() => alert("Copied URLs to clipboard:\n" + selectedUrls.join("\n")))
                .catch(err => console.error("Failed to copy:", err));
        });
    }
});
