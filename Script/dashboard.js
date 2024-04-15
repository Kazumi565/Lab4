let pageEventListeners = [];

function addStoredEventListener(element, eventType, callback, page) {
    let pageData = pageEventListeners.find(data => data.page === page);

    if (!pageData) {
        pageData = { page, elements: [] };
        pageEventListeners.push(pageData);
    }

    let elementData = pageData.elements.find(data => data.element === element);

    if (!elementData) {
        elementData = { element, listeners: [] };
        pageData.elements.push(elementData);
    } else {
        const existingListener = elementData.listeners.find(listener =>
            listener.eventType === eventType && listener.callback === callback
        );

        if (existingListener) {
            console.warn("Event listener already exists for this element and callback.");
            return;
        }
    }

    elementData.listeners.push({ eventType, callback });
    element.addEventListener(eventType, callback);
}


function removeAllPageEventListeners(page) {
    const pageDataIndex = pageEventListeners.findIndex(data => data.page === page);

    if (pageDataIndex !== -1) {
        const pageData = pageEventListeners[pageDataIndex];
        pageData.elements.forEach(elementData => {
            elementData.listeners.forEach(({ eventType, callback }) => {
                elementData.element.removeEventListener(eventType, callback);
            });
            elementData.listeners = [];
        });

        pageEventListeners.splice(pageDataIndex, 1);
    }
}


function login() {
    window.open("LoginPage.php", "_self");
}

function logout() {
    const url = "dynamicFunctions.php";
    let functionBody = {
        functionName: "delete_session",
    };

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(functionBody)
    })
        .catch(error => {
            console.error(error);
        });

    window.open("LoginPage.php", "_self");
}

async function change_theme() {
    const url = "dynamicFunctions.php";
    let functionBody = {
        functionName: "change_theme",
    };

    let theme = "";

    try {
        theme = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(functionBody)
        }).then(response => {
            return response.text();
        })
    }
    catch (error) {
        console.error(error);
    }

    let $theme_link = document.getElementById("theme");
    $theme_link.href = 'Styles/' + theme + '.css';
}

function capitalizeFirstLetter(inputString) {
    if (inputString.length > 0) {
        var resultString = inputString.charAt(0).toUpperCase() + inputString.slice(1);
        return resultString;
    }
    return "";
}

function setActiveNavButton(buttonId) {
    var navButtons = document.getElementsByClassName('nav_button');

    for (var i = 0; i < navButtons.length; i++) {
        var button = navButtons[i];
        if (button.classList.contains('active')) {
            button.classList.remove('active');
        }
    }

    var activeNavButton = document.getElementById(buttonId);
    if (activeNavButton) {
        activeNavButton.classList.add('active');
    } else {
        console.error("Element with ID '" + buttonId + "' not found.");
    }
}


async function set_data_table_options() {
    const url = "dynamicFunctions.php";
    const $tableSelector = document.getElementById("tableSelector");

    let functionBody = {
        functionName: "get_database_table_names",
    };

    let tableNames = null;

    try {
        const tableNamesText = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(functionBody)
        }).then(response => {
            return response.text();
        })

        if (tableNamesText == "") {
            return;
        }
        tableNames = JSON.parse(tableNamesText);
    }
    catch (error) {
        console.error(error);
    }

    let options = "";

    tableNames.forEach(function (name) {
        options += "<option value='" + name + "'>" + capitalizeFirstLetter(name.replace(/_/g, ' ')); + "</option>";
    });
    $tableSelector.innerHTML = options;
}

async function set_data_table_content() {
    const url = "dynamicFunctions.php";
    const $table = document.getElementById("dataTable");
    const selectElement = document.getElementById("tableSelector");
    const selectedIndex = selectElement.selectedIndex;

    if (selectedIndex == -1) {
        $table.innerHTML = ('No table selected.');
        return;
    }

    const tableName = selectElement.options[selectedIndex].value;

    let functionBody = {
        functionName: "get_database_table_data",
        data: {
            tableName: tableName
        }
    };

    let tableData = null;

    try {
        let tableDataText = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(functionBody)
        }).then(response => {
            return response.text();
        });

        if (tableDataText == "[]") {
            $table.innerHTML = ('No data available.');
            return;
        }
        tableData = JSON.parse(tableDataText);

    } catch (error) {
        console.error(error);
    }

    let tableContent = "";
    let headers = Object.keys(tableData[0]);

    tableContent += '<thead><tr>';
    headers.forEach(function (header) {
        tableContent += '<th>' + capitalizeFirstLetter(header.replace(/_/g, ' ')); + '</th>';
    });
    tableContent += '</tr></thead>';

    tableContent += '<tbody>';
    tableData.forEach(function (rowData) {
        tableContent += '<tr data-row_id="' + rowData['id'] + '">';
        headers.forEach(function (header) {
            tableContent += '<td data-column_name="' + header + '">' + rowData[header] + '</td>';
        });
        tableContent += '</tr>';
    });
    tableContent += '</tbody>';

    $table.innerHTML = tableContent;
}

async function load_data_table() {
    removeAllPageEventListeners();
    setActiveNavButton("navDataTableButton");
    const $contentWrap = document.getElementById("contentWrap");
    const url = "dynamicFunctions.php";

    let functionBody = {
        functionName: "get_data_table_template",
    };

    try {
        const tableTemplate = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(functionBody)
        }).then(response => {
            return response.text();
        });

        $contentWrap.innerHTML = tableTemplate;

        await set_data_table_options();
        await set_data_table_content();

    }
    catch (error) {
        console.error(error);
    }

    const $tableSelector = document.getElementById("tableSelector");
    addStoredEventListener($tableSelector, "change", set_data_table_content, "dashboard");
}

async function set_data_table_content() {
    const url = "dynamicFunctions.php";
    const $table = document.getElementById("dataTable");
    const selectElement = document.getElementById("tableSelector");
    const selectedIndex = selectElement.selectedIndex;

    if (selectedIndex == -1) {
        $table.innerHTML = ('No table selected.');
        return;
    }

    const tableName = selectElement.options[selectedIndex].value;

    let functionBody = {
        functionName: "get_database_table_data",
        data: {
            tableName: tableName
        }
    };

    let tableData = null;

    try {
        let tableDataText = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(functionBody)
        }).then(response => {
            return response.text();
        });

        if (tableDataText == "[]") {
            $table.innerHTML = ('No data available.');
            return;
        }
        tableData = JSON.parse(tableDataText);

    } catch (error) {
        console.error(error);
    }

    let tableContent = "";
    let headers = Object.keys(tableData[0]);

    tableContent += '<thead><tr>';
    headers.forEach(function (header) {
        tableContent += '<th>' + capitalizeFirstLetter(header.replace(/_/g, ' ')) + '</th>';
    });
    tableContent += '<th>Actions</th>';
    tableContent += '</tr></thead>';

    tableContent += '<tbody>';
    tableData.forEach(function (rowData) {
        tableContent += '<tr data-row_id="' + rowData['id'] + '">';
        headers.forEach(function (header) {
            tableContent += '<td data-column_name="' + header + '">' + rowData[header] + '</td>';
        });
        tableContent += '<td><button onclick="deleteRow(' + rowData['id'] + ')">Delete</button>';
        tableContent += '<button onclick="modifyRow(' + rowData['id'] + ')">Modify</button></td>';
        tableContent += '</tr>';
    });
    tableContent += '</tbody>';

    $table.innerHTML = tableContent;
}

async function load_description() {
    removeAllPageEventListeners();
    setActiveNavButton("navDescriptionButton");

    let $contentWrap = document.getElementById("contentWrap");
    const url = "dynamicFunctions.php";
    let functionBody = {
        functionName: "get_site_description",
    };

    try {
        const description = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(functionBody)
        }).then(response => {
            return response.text();
        });

        $contentWrap.innerHTML = description;
    }
    catch (error) {
        console.error(error);
    }
}

window.onload = load_description();