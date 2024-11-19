let loaded = false;
let astronauts = undefined;
let selects = [];

document.addEventListener("DOMContentLoaded", function () {
    fetch('../php/get_astronauts.php')
    .then(response => response.json())
    .then((responseJson) => { astronauts = responseJson; loaded = true; })
    .catch(error => console.error('Error fetching options:', error));
});

function add_select(value) {
    if (!loaded) {
        return;
    }

    // Create a container div for the select and remove button
    let containerDiv = document.createElement('div');
    containerDiv.classList.add('select-container'); // Optional: Add a class for styling

    // Create the select element
    let selectElement = document.createElement('select');
    astronauts.forEach(option => {
        let optionElement = document.createElement('option');
        optionElement.value = option.first_name + " " + option.last_name;
        optionElement.textContent = option.first_name + " " + option.last_name;
        selectElement.appendChild(optionElement);
    });

    if(value){
        selectElement.value = value;
    }
    else{
        for (let i = 0; i < selects.length; i++) {
            if(selects[i].selectedIndex != i){
                selectElement.selectedIndex = i
            }
            console.log(i)
            console.log(selects[i].selectedIndex)
        }
    }

    // Create the remove button
    let removeButton = document.createElement('button');
    removeButton.textContent = 'Remove';
    removeButton.type = 'button';

    // Add event listener for removing the select element
    removeButton.addEventListener('click', function () {
        containerDiv.remove(); // Remove the entire container div (select + button)

        const index = selects.indexOf(selectElement);
        if (index > -1) {
            selects.splice(index, 1);
        }

        updateOptions(); // Update options in all remaining selects
    });

    // Add event listener to update options when a selection is made
    selectElement.addEventListener('change', function () {
        updateOptions();
    });

    // Append the select element and the remove button to the container div
    containerDiv.appendChild(selectElement);
    containerDiv.appendChild(removeButton);

    // Append the container div to the "add_selects_here" div
    document.getElementById("add_selects_here").appendChild(containerDiv);
    selects.push(selectElement);

    // After adding the new select, update all the options in other selects
    updateOptions();
}

function updateOptions() {
    let indexes = []
    let optionsAll = []
    selects.forEach(select => {
        indexes.push(select.selectedIndex)
        optionsAll.push(select.options)
    });

    //console.log(indexes)

    optionsAll.forEach(options => {
        for (let i = 0; i < options.length; i++) {
            options[i].disabled = indexes.includes(i) ? true : false;
        }
    });
}