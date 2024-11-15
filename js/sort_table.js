function sortTable(columnIndex, direction) {
    const table = document.querySelector("table");
    let rows = Array.from(table.rows).slice(1); // Get rows, excluding the header row

    let isAscending = !(direction === "down");
    console.log(isAscending)

    // Sort rows based on the content of the specified column
    rows.sort((a, b) => {
        const cellA = a.cells[columnIndex].innerText.toLowerCase();
        const cellB = b.cells[columnIndex].innerText.toLowerCase();
        console.log("sorting")

        if (cellA < cellB) return isAscending ? -1 : 1;
        if (cellA > cellB) return isAscending ? 1 : -1;
        return 0;
    });

    // Reattach sorted rows to the table
    const tbody = table.tBodies[0];
    rows.forEach(row => tbody.appendChild(row));
}