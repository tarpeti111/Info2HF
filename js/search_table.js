document.getElementById("searchInput").addEventListener("keyup", () =>{
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.querySelector("table");
    let rows = Array.from(table.rows).slice(1);
    let resultRows = [];

    rows.forEach((row) => {
        let data = Array.from(row.cells)
        row.style.display = "none"
        for (let i = 0; i < data.length; i++) {
            if(data[i].textContent.toLocaleLowerCase().includes(filter) && !data[i].querySelector('a')){
                row.style.display = ""
                break;
            }
        }
    })
})