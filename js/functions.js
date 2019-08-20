// ------- FUNCTIONS -------

function increment(id) {
    var btn = document.getElementById(id);
    AppGlobals.grid[id] = (AppGlobals.grid[id] === 9) ? 1 : ++AppGlobals.grid[id];
    btn.innerHTML = AppGlobals.grid[id];
    event.preventDefault();
}

function fetchGrid(level) {
    sessionStorage.clear();
    fetch(AppGlobals.hostname + '/services/get_puzzle/' + level).then((response) => {
        return response.json();
    }).then((result) => {
        AppGlobals.grid = result.grid;
        // sessionStorage must be cleared on new grid request
        if (!sessionStorage.getItem('initial')) {
            // save the new grid to sessionStorage
            sessionStorage.setItem('initial', JSON.stringify(AppGlobals.grid));
        }
    }).then(() => {
        // clear the HTML
        clearGrid();
        renderGrid(AppGlobals.grid);
        displayGrid();
    });
}

/*
 * XMLHttpRequest object creation in a factory-like wrapper
 * @returns {ActiveXObject|XMLHttpRequest}
 */
function createXMLHttpRequestObject() {
    var xmlHttp;
    if (window.XMLHttpRequest) {
        xmlHttp = new XMLHttpRequest();
    } else {
        try {
            xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
        } catch (err) {
            displayAlert('error', err.toString() + '. Looks like we do not support Your browser.');
        }
    }
    return xmlHttp;
}

/*
 * Sends the request
 * @param {string} url
 * @param {string} method
 * @param {FormData} data
 * @param {string} requestedObject
 * @returns {undefined}
 */
function process(url, method, data, requestedObject) {
    // If request object was created, send it
    if (AppGlobals.request) {
        try {
            AppGlobals.request.open(method, url, true);
            // switch between PDF and regular grid requests
            if (requestedObject === 'PDF') {
                AppGlobals.request.responseType = 'blob';
            } else if (requestedObject === 'Grid') {
                AppGlobals.request.responseType = 'text';
            }
            AppGlobals.request.onreadystatechange = handleResponse;
            AppGlobals.request.send(data);
        } catch (err) {
            displayAlert('error', err.toString() + '. Please, try later.');
        }
    }
}

/*
 * Response handler
 * @returns {undefined}
 */
function handleResponse() {

    if (AppGlobals.request.readyState === XMLHttpRequest.DONE) {
        if (AppGlobals.request.status === 200) {
            var contentType = AppGlobals.request.getResponseHeader('Content-Type');
            // switch between response content types
            try {
                switch (contentType) {
                    case 'application/pdf':
                        // create a download link for the PDF document
                        var downloadUrl = window.URL.createObjectURL(this.response);
                        var a = document.createElement('a');
                        a.download = 'sudoku.pdf';
                        a.href = downloadUrl;
                        document.body.appendChild(a);
                        a.click();
                        break;
                    case 'application/json':
                        var json_response = JSON.parse(AppGlobals.request.responseText);
                        if (json_response['grid']) {
                            // sessionStorage must be cleared on requestGrid() call
                            if (!sessionStorage.getItem('initial')) {
                                // save the new grid to sessionStorage
                                sessionStorage.setItem('initial', AppGlobals.request.responseText);
                            }
                            // clear the HTML
                            clearGrid();
                            // render new grid as HTML table
                            renderGrid(json_response['grid']);
                            displayGrid();
                        } else if (json_response['wrong_cells']) {
                            displayWrongCells(json_response['wrong_cells']);
                        } else if (json_response['message']) {
                            displayAlert('congrats', json_response['message']);
                        } else if (json_response['error']) {
                            displayAlert('error', json_response['error']);
                        }
                        break;
                }
            } catch (err) {
                displayAlert('error', err.toString());
            }
        } else
            displayAlert('error', AppGlobals.request.statusText);
    }
}

/*
 * Prints error mesages
 * @param {string} message
 * @returns {undefined}
 */
function displayAlert(type, msg) {
    var alertElement = document.getElementById('alert');
    var msgDisplayClass = '';
    switch (type) {
        case 'error':
            msgDisplayClass = 'error-msg';
            break;
        case 'congrats':
            msgDisplayClass = 'congrats-msg';
            break;
        default:
            msgDisplayClass = 'default-msg';
    }
    alertElement.classList.add(msgDisplayClass);
    document.getElementById('message').innerHTML = msg;
    alertElement.style.display = 'block';
}

function displayWrongCells(wrongCells) {
    var cells = document.getElementsByClassName('cell wrong');
    if (cells.length !== 0) {
        removeWrongClass(cells);
    }
    for (var i = 0; i < wrongCells.length; i++) {
        var cell = document.getElementById(wrongCells[i]);
        cell.classList.add('wrong');
    }
}

function removeWrongClass(cells) {
    cells[0].classList.remove('wrong');
    if (cells[0]) {
        removeWrongClass(cells);
    }
}

/*
 * Renders grid[] as HTML table
 * @param {Array} data
 * @returns {undefined}
 */
function renderGrid(data) {
    var HTMLtable = '<form id="sudoku"><table>';
    for (var i = 0; i < 9; i++) {
        HTMLtable += '<tr>';
        for (var j = 0; j < 9; j++) {
            var id = (i * 9 + j);
            var borderClass = 'cell';

            // add thicker vertical lines
            borderClass += (id % 3 === 0) ? ' border-left' : '';
            borderClass += (id % 9 === 8) ? ' border-right' : '';

            //add thicker horizontal lines
            borderClass += (id < 9) ? ' border-top' : '';
            borderClass += (id % 27 >= 18) ? ' border-bottom' : '';
            HTMLtable += '<td class="' + borderClass + '">' +
                    ((data[id] !== 0) ? data[id] :
                            '<button class="click" id="' + id + '" onclick="increment(this.id)">') +
                    '&nbsp;</button></td>';
        }
        HTMLtable += '</tr>';
    }
    HTMLtable += '</table></form>';
    document.getElementById('table').insertAdjacentHTML('beforeend', HTMLtable);
}

/*
 * Clears HTML table
 * @returns {undefined}
 */
function clearGrid() {
    document.getElementById('table').innerHTML = '';
}

/*
 * Gathers inputs from grid < td > tags
 * @returns {FormData|gatherGridInputData.grid}
 */
function gatherGridInputs() {
    var cells = document.getElementsByClassName('inputs');
    if (cells.length !== 0) {
        var grid = new Array();
        for (var i = 0; i < cells.length; i++) {
            grid.push((parseInt(cells[i].value)) ? parseInt(cells[i].value) : '0');
        }
        return grid;
    }
    return false;
}

// TODO: make a single function for switching between grid, pdf-config and 
// initial windows

/*
 * Switches classes for grid view
 * @returns {undefined}
 */
function displayGrid() {
    document.getElementById('pdf-config').classList.add('hidden');
    document.getElementById('main-container').classList.remove('hidden');
    document.getElementById('grid').classList.remove('hidden');
    document.getElementById('main-container').classList.add('basicTransition');
    document.getElementById('controls').classList.add('controlsTransition');
}

/*
 * Switches classes for PDF download configuration view
 * @returns {undefined}
 */
function displayPDFConfig() {
    document.getElementById('main-container').classList.remove('hidden');
    document.getElementById('grid').classList.add('hidden');
    clearGrid();
    document.getElementById('pdf-config').classList.remove('hidden');
}

