const request = new XMLHttpRequest();

// RESET BUTTON LISTENER
document.getElementById('reset').addEventListener('click', function () {
    clear();
    renderGrid(JSON.parse(sessionStorage.getItem('initial'))['grid']);
});

// SOLVE BUTTON LISTENER
document.getElementById('solution').addEventListener('click', function () {
    sendRequest('http://localhost/Sudoku/api/solve.php', 'POST', gatherFormData());
});


request.onreadystatechange = function () {
    if (request.readyState === XMLHttpRequest.DONE) {
        if (request.status >= 200 && request.status < 400) {
            if (JSON.parse(request.responseText)['grid']) {
                if (!sessionStorage.getItem('initial')) {
                    sessionStorage.setItem('initial', request.responseText);
                }
                clear();
                renderGrid(JSON.parse(request.responseText)['grid']);
                displayGrid();
            } else if (JSON.parse(request.responseText)['error']) {
                console.log(JSON.parse(request.responseText)['error']);
            } else
                console.log('Something went wrong...');
        } else
            console.log('Server error...');
    }
}

document.getElementById('get-pdf').addEventListener('click', function () {
    var url = 'http://localhost/Sudoku/api/get_pdf.php';
    //var selector = document.getElementById('level');
    //var level = selector[selector.selectedIndex].value;
    var request = new XMLHttpRequest();
    request.open('GET', url, true);
    request.responseType = "blob";
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
    request.onload = function () {
        if (request.readyState === XMLHttpRequest.DONE) {
            if (request.status >= 200 && request.status < 400) {
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
            } else {
                console.log("Server error...");
            }
        }
    };
    request.send();
});

// FUNCTIONS

function requestGrid(level) {
    sessionStorage.clear();
    sendRequest('http://localhost/Sudoku/api/get_puzzle/' + level, 'GET');
}

function sendRequest(url, method, data) {
    request.open(method, url, true);
    request.send(data);
}

function gatherFormData() {
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

function clear() {
    document.getElementById('table').innerHTML = '';
}

function getGridInputs() {
    var inputs = document.getElementById('sudoku').getElementsByTagName('input');
    var inputArray = new Array();
    if (inputs.length !== 20 && inputs.length !== 40 && inputs.length !== 50) {
        console.log('ERROR: problem with inputs')
    }

    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].length > 0) {
            inputArray[inputs[i].name] = '0';
        }
        inputArray[inputs[i].name] = inputs[i].value;
    }
}


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
    clear();
    document.getElementById('pdf-config').classList.remove('hidden');
}
