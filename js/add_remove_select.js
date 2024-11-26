var loaded = false;
let responseArray = undefined;
let selects = [];
let contentToAdd = undefined;

function loadAstronauts() {
    return fetch('../php/get_astronauts.php')
        .then(response => response.json())
        .then(responseJson => {
            responseArray = responseJson;
            loaded = true;
            contentToAdd = "astronaut";
        })
        .catch(error => console.error('Error fetching options:', error));
}

function loadShips() {
    return fetch('../php/get_ships.php')
        .then(response => response.json())
        .then(responseJson => {
            responseArray = responseJson;
            loaded = true;
            contentToAdd = "spaceship";
        })
        .catch(error => console.error('Error fetching options:', error));
}

function add_select(value) {
    if(!loaded) {
        return;
    }
    
    // Create a container div for the select and remove button
    let containerDiv = document.createElement('div');
    containerDiv.classList.add('select-container'); // Optional: Add a class for styling

    // Create the select element
    let selectElement = document.createElement('select');
    addOptions(selectElement, contentToAdd);
    setValue(selectElement, value);
    const removeButton = addRemoveButton();

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

function addOptions(selectElement, contentToAdd){
    if(contentToAdd === "astronaut") {
        selectElement.name = "astronauts[]"
        responseArray.forEach(option => {
            let optionElement = document.createElement('option');
            optionElement.value = option.first_name + " " + option.last_name;
            optionElement.textContent = option.first_name + " " + option.last_name;
            selectElement.appendChild(optionElement);
        });
    }
    else if(contentToAdd === "spaceship") {
        selectElement.name = "ships[]"
        responseArray.forEach(option => {
            let optionElement = document.createElement('option');
            optionElement.value = option.name;
            optionElement.textContent = option.name;
            selectElement.appendChild(optionElement);
        });
    }
}

function setValue(selectElement, value) {
    if (value) {
        // Set the value directly if provided
        selectElement.value = value;
    } else {
        // Determine which index to set if no value is provided
        let set = false;
        let i = 0;
        let indexes = selects.map(select => select.selectedIndex);

        for (; i < selects.length; i++) {
            if (!indexes.includes(i)) {
                selectElement.selectedIndex = i;
                set = true;
                break;
            }
        }

        if (!set) {
            // If no unused index is found, default to the last index
            selectElement.selectedIndex = i;
        }
    }
}

function addRemoveButton(selectElement){
    // Create the remove button
    let removeButton = document.createElement('button');
    removeButton.textContent = 'Remove';
    removeButton.type = 'button';
    removeButton.classList.add('button-delete')

    // Add event listener for removing the select element
    removeButton.addEventListener('click', function () {
        containerDiv.remove(); // Remove the entire container div (select + button)

        const index = selects.indexOf(selectElement);
        if (index > -1) {
            selects.splice(index, 1);
        }

        updateOptions(); // Update options in all remaining selects
    });
    return removeButton;
}

function updateOptions(){
    let usedOptions = [];

    selects.forEach((select) => {
        usedOptions.push(select.selectedIndex)
    })

    selects.forEach((select) => {
        for (let i = 0; i < select.options.length; i++) {
            select.options[i].disabled = usedOptions.includes(i) && select.selectedIndex != i ? true : false;
        }
    })
}