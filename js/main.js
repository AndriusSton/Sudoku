const request = createXMLHttpRequestObject();
const hostname = location.protocol + "//" + location.hostname + ((location.port) ? ":" + location.port : "");

/*
 * RESET BUTTON LISTENER
 * @returns {undefined}
 */
document.getElementById('reset-btn').addEventListener('click', function () {
    clearGrid();
    renderGrid(JSON.parse(sessionStorage.getItem('initial'))['grid']);
});

/*
 * SOLVE BUTTON LISTENER
 * @returns {undefined}
 */
document.getElementById('solution-btn').addEventListener('click', function () {
    var url = hostname + "/services/solve.php";
    var initial = JSON.parse(sessionStorage.getItem('initial'))['grid'];
    var gridToSend = new FormData();
    for (var i = 0; i < initial.length; i++) {
        gridToSend.append(i, initial[i]);
    }
    process(url, 'POST', gridToSend, 'Grid');
});

/*
 * CHECK BUTTON LISTENER
 * @returns {undefined}
 */
document.getElementById('check-btn').addEventListener('click', function () {
    var url = hostname + "/services/check.php";
    var initial = JSON.stringify(JSON.parse(sessionStorage.getItem('initial'))['grid']);
    var inputs = gatherGridInputData()? gatherGridInputData() : null;
    
    if (inputs) {
        var gridToSend = new FormData();
        gridToSend.append('initial', initial);
        gridToSend.append('solution', JSON.stringify(inputs));
        process(url, 'POST', gridToSend, 'Grid');
    } else {
        displayError('Nothing to solve...');
    }

});


/*
 * GET PDF BUTTON LISTENER
 * @returns {undefined}
 */
document.getElementById('get-pdf-btn').addEventListener('click', function () {
    var formData = new FormData();
    var selector = document.getElementById('level');
    var level = selector[selector.selectedIndex].value;
    var numOfGrids = document.getElementById('num-of-grids').value;
    var url = hostname + "/services/get_pdf.php";
    formData.append('level', level);
    formData.append('numOfGrids', numOfGrids);
    process(url, 'POST', formData, 'PDF');
});

// ------- FUNCTIONS -------

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
            displayError(err.toString() + '. Looks like we do not support Your browser.');
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
    if (request) {
        try {
            request.open(method, url, true);
            // switch between PDF and regular grid requests
            if (requestedObject === 'PDF') {
                request.responseType = 'blob';
            } else if (requestedObject === 'Grid') {
                request.responseType = 'text';
            }
            request.onreadystatechange = handleResponse;
            request.send(data);
        } catch (err) {
            displayError(err.toString() + '. Please, try later.');
        }
    }
}

/*
 * Response handler
 * @returns {undefined}
 */
function handleResponse() {

    if (request.readyState === XMLHttpRequest.DONE) {
        if (request.status === 200) {
            var contentType = request.getResponseHeader('Content-Type');
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

                        var json_response = JSON.parse(request.responseText);
                        if (json_response['grid']) {
                            // sessionStorage must be cleared on requestGrid() call
                            if (!sessionStorage.getItem('initial')) {
                                // save the new grid to sessionStorage
                                sessionStorage.setItem('initial', request.responseText);
                            }
                            // clear the HTML
                            clearGrid();
                            // render new grid as HTML table
                            renderGrid(JSON.parse(request.responseText)['grid']);
                            displayGrid();
                        } else if (json_response['message']) {
                            displayError(json_response['message']);
                        } else if (json_response['error']) {
                            displayError(json_response['error']);
                        }
                        break;
                }
            } catch (err) {
                displayError(err.toString());
            }
        } else
            displayError(request.statusText);
    }
}

/*
 * Prints error mesages
 * @param {string} message
 * @returns {undefined}
 */
function displayError(message) {
    var alertElement = document.getElementById('alert');
    document.getElementById('message').innerHTML = message;
    alertElement.style.display = 'block';
}

/*
 * Clears sessionStorage and forms request url for new grid with a selected level
 * @param {string} level
 * @returns {undefined}
 */
function requestGrid(level) {
    sessionStorage.clear();
    process(hostname + '/services/get_puzzle/' + level, 'GET', null, 'Grid');
}

/*
 * Renders grid[] as HTML table
 * @param {Array} data
 * @returns {undefined}
 */
function renderGrid(data) {
    var HTMLtable = '<form id="sudoku"><table>';
    for (var i = 0; i < 9; i++) {
        HTMLtable += '<tr id="' + i + '">';
        for (var j = 0; j < 9; j++) {
            HTMLtable += '<td id="' + ((i * 9) + j) + '">' +
                    ((data[(i * 9) + j] !== 0) ? data[(i * 9) +
                            j] : '<input class="cells" id="cell-' + ((i * 9) + j) + '" name="' + ((i * 9) + j) +
                            '" type="text" pattern="[1-9]{1}" autocomplete="off">') +
                    '</td>';
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
function gatherGridInputData() {
    var cells = document.getElementsByClassName('cells');
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

