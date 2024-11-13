function sortTable(columnIndex) {
    const table = document.querySelector("table");
    const headers = table.querySelectorAll("th");
    let rows = Array.from(table.rows).slice(1); // Get rows, excluding the header row

    // Get the header cell for the column being sorted
    const header = headers[columnIndex];

    // Check if the column is sorted in ascending order; default to true if not set
    let isAscending = header.getAttribute("data-sort-asc") === "true";
    if (header.getAttribute("data-sort-asc") === null) {
        isAscending = true; // Default to ascending if not set
    }

    // Sort rows based on the content of the specified column
    rows.sort((a, b) => {
        const cellA = a.cells[columnIndex].innerText.toLowerCase();
        const cellB = b.cells[columnIndex].innerText.toLowerCase();

        if (cellA < cellB) return isAscending ? -1 : 1;
        if (cellA > cellB) return isAscending ? 1 : -1;
        return 0;
    });

    // Toggle the sort direction for the current column
    header.setAttribute("data-sort-asc", !isAscending);

    // Reset other columns' data-sort-asc attributes
    headers.forEach((th, index) => {
        if (index !== columnIndex) {
            th.removeAttribute("data-sort-asc"); // Remove sort state for other columns
        }
    });

    // Reattach sorted rows to the table
    const tbody = table.tBodies[0];
    rows.forEach(row => tbody.appendChild(row));
}