const request = createXMLHttpRequestObject();
// RESET BUTTON LISTENER
document.getElementById('reset-btn').addEventListener('click', function () {
    clearGrid();
    renderGrid(JSON.parse(sessionStorage.getItem('initial'))['grid']);
});

// SOLVE BUTTON LISTENER
document.getElementById('solution-btn').addEventListener('click', function () {
    process('http://localhost/Sudoku/services/solve.php', 'POST', gatherGridInputData(), 'Grid');
});

// GET PDF BUTTON LISTENER
document.getElementById('get-pdf-btn').addEventListener('click', function () {
    var formData = new FormData();
    var selector = document.getElementById('level');
    var level = selector[selector.selectedIndex].value;
    var numOfGrids = document.getElementById('num-of-grids').value;
    formData.append('level', level);
    formData.append('numOfGrids', numOfGrids);
    process('http://localhost/Sudoku/services/get_pdf.php', 'POST', formData, 'PDF');
});

// FUNCTIONS
function requestGrid(level) {
    sessionStorage.clear();
    process('http://localhost/Sudoku/services/get_puzzle/' + level, 'GET', null, 'Grid');
}

function sendRequest(url, method, data) {
    request.open(method, url, true);
    request.send(data);
}

function gatherGridInputData() {
    var cells = document.getElementsByTagName('td');
    var grid = new FormData();
    for (var i = 0; i < cells.length; i++) {
        if (cells[i].getElementsByTagName('input').length > 0) {
            grid.append(cells[i].id, 0);
        } else {
            grid.append(cells[i].id, cells[i].innerHTML);
        }
    }
    return grid;
}

function renderGrid(data) {
    var HTMLtable = '<form id="sudoku"><table>';
    for (var i = 0; i < 9; i++) {
        HTMLtable += '<tr id="' + i + '">';
        for (var j = 0; j < 9; j++) {
            HTMLtable += '<td id="' + ((i * 9) + j) + '">' +
                    ((data[(i * 9) + j] !== 0) ? data[(i * 9) +
                            j] : '<input name="' + ((i * 9) + j) +
                            '" type="text" pattern="[1-9]{1}" autocomplete="off"/>') +
                    '</td>';
        }
        HTMLtable += '</tr>';
    }
    HTMLtable += '</table></form>';
    document.getElementById('table').insertAdjacentHTML('beforeend', HTMLtable);
}

function clearGrid() {
    document.getElementById('table').innerHTML = '';
}

// TODO: make a single function for switching between grid, pdf-config and 
// initial windows
function displayGrid() {
    document.getElementById('pdf-config').classList.add('hidden');
    document.getElementById('main-container').classList.remove('hidden');
    document.getElementById('grid').classList.remove('hidden');
    document.getElementById('main-container').classList.add('basicTransition');
    document.getElementById('controls').classList.add('controlsTransition');
}

function displayPDFConfig() {
    document.getElementById('main-container').classList.remove('hidden');
    document.getElementById('grid').classList.add('hidden');
    clearGrid();
    document.getElementById('pdf-config').classList.remove('hidden');
}


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

function process(url, method, data, requestedObject) {
    if (request) {
        try {
            request.open(method, url, true);
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

function handleResponse() {

    if (request.readyState === XMLHttpRequest.DONE) {
        if (request.status === 200) {
            var contentType = request.getResponseHeader('Content-Type');
            
            try {
                switch (contentType) {
                    case 'application/pdf':
                        var downloadUrl = window.URL.createObjectURL(this.response);
                        var a = document.createElement('a');
                        a.download = 'sudoku.pdf';
                        a.href = downloadUrl;
                        document.body.appendChild(a);
                        a.click();
                        break;
                    case 'application/json':
                        if (JSON.parse(request.responseText)['grid']) {
                            if (!sessionStorage.getItem('initial')) {
                                sessionStorage.setItem('initial', request.responseText);
                            }
                            clearGrid();
                            renderGrid(JSON.parse(request.responseText)['grid']);
                            displayGrid();
                        } else {
                            displayError(request.statusText);
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

function displayError(message){
    var alertElement = document.getElementById('alert');
    document.getElementById('message').innerHTML = message;
    alertElement.style.display = 'block';
}