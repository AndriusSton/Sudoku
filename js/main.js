const request = createXMLHttpRequestObject();
// RESET BUTTON LISTENER
document.getElementById('reset').addEventListener('click', function () {
    clearGrid();
    renderGrid(JSON.parse(sessionStorage.getItem('initial'))['grid']);
});

// SOLVE BUTTON LISTENER
document.getElementById('solution').addEventListener('click', function () {
    process('http://localhost/Sudoku/api/solve.php', 'POST', gatherGridInputData(), 'Grid');
});

// GET PDF BUTTON LISTENER
document.getElementById('get-pdf').addEventListener('click', function () {
    var formData = new FormData();
    var selector = document.getElementById('level');
    var level = selector[selector.selectedIndex].value;
    var numOfGrids = document.getElementById('num-of-grids').value;
    formData.append('level', level);
    formData.append('numOfGrids', numOfGrids);
    process('http://localhost/Sudoku/api/get_pdf.php', 'POST', formData, 'PDF');
});

// FUNCTIONS
function requestGrid(level) {
    sessionStorage.clear();
    process('http://localhost/Sudoku/api/get_puzzle/' + level, 'GET', null, 'Grid');
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
            console.log(err.toString());
        }
    }
    return xmlHttp;
}

function process(url, method, data, requestedObject) {
    if (request) {
        try {
            request.open(method, url, true);
            if (requestedObject === 'PDF') {
                request.responseType = "blob";
            } else if (requestedObject === 'Grid') {
                request.responseType = "text";
            }
            request.onreadystatechange = handleResponse;
            request.send(data);
        } catch (err) {
            console.log(err.toString());
        }
    }
}

function handleResponse() {

    if (request.readyState === XMLHttpRequest.DONE) {
        if (request.status === 200) {
            try {
                switch (request.responseType) {
                    case 'blob':
                        var downloadUrl = window.URL.createObjectURL(this.response);
                        var a = document.createElement("a");
                        //a.download = 'sudoku.pdf';
                        a.href = downloadUrl;
                        document.body.appendChild(a);
                        a.click();
                        window.open(downloadUrl, 'sudoku.pdf');
                        // remove `a` following `Save As` dialog, 
                        // `window` regains `focus`
                        document.body.removeChild(a);
                        URL.revokeObjectURL(downloadUrl);
                        break;
                    case 'text':
                        if (JSON.parse(request.responseText)['grid']) {
                            if (!sessionStorage.getItem('initial')) {
                                sessionStorage.setItem('initial', request.responseText);
                            }
                            clearGrid();
                            renderGrid(JSON.parse(request.responseText)['grid']);
                            displayGrid();
                        } else if (JSON.parse(request.responseText)['error']) {
                            console.log(JSON.parse(request.responseText)['error']);
                        } else {
                            console.log('Something went wrong...')
                        }
                        break;
                }


            } catch (err) {
                console.log(err.toString());
            }
        } else
            console.log(request.statusText);
    }
}