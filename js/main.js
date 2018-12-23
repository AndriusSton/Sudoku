// BUTTON LISTENERS
document.getElementById('easy').addEventListener('click', function () {
    requestGrid('easy');
});
document.getElementById('medium').addEventListener('click', function () {
    requestGrid('medium');
});
document.getElementById('hard').addEventListener('click', function () {
    requestGrid('hard');
});
document.getElementById('reset').addEventListener('click', function () {
    clear();
    renderGrid(JSON.parse(sessionStorage.getItem('initial'))['grid']);
});
document.getElementById('solve').addEventListener('click', function () {
    var url = 'http://localhost/Sudoku/api/solve.php';
    var cells = document.getElementsByTagName('td');
    var grid = new FormData();
    for (var i = 0; i < cells.length; i++) {
        if (cells[i].getElementsByTagName('input').length > 0) {
            grid.append(cells[i].id, 0);
        } else {
            grid.append(cells[i].id, cells[i].innerHTML);
        }
    }
    var request = new XMLHttpRequest();
    request.open('POST', url, true);
    request.send(grid);
    request.onreadystatechange = function () {
        if (request.readyState === XMLHttpRequest.DONE) {
            if (request.status >= 200 && request.status < 400) {
                if (JSON.parse(request.responseText)['grid']) {
                    clear();
                    renderGrid(JSON.parse(request.responseText)['grid']);
                } else if (JSON.parse(request.responseText)['error']) {
                    console.log(JSON.parse(request.responseText)['error']);
                } else
                    console.log('Something went wrong...');
            } else
                console.log('Server error...');
        }
    };
});
document.getElementById('getpdf').addEventListener('click', function () {
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
    var url = 'http://localhost/Sudoku/api/get_puzzle/';
    var request = new XMLHttpRequest();
    request.open('GET', url + level, true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.onreadystatechange = function () {
        if (request.readyState === XMLHttpRequest.DONE) {
            if (request.status >= 200 && request.status < 400) {
                if (JSON.parse(request.responseText)['grid']) {
                    sessionStorage.setItem('initial', request.responseText);
                    clear();
                    renderGrid(JSON.parse(request.responseText)['grid']);
                } else if (JSON.parse(request.responseText)['error']) {
                    console.log(JSON.parse(request.responseText)['error']);
                } else
                    console.log("Something went wrong...");
            } else {
                console.log("Server error...");
            }
        }
    };

        request.send();
    }

    function renderGrid(data) {
        var HTMLtable = '<form id="sudoku"><table>';
        for ($i = 0; $i < 9; $i++) {
            HTMLtable += '<tr id="' + $i + '">';
            for ($j = 0; $j < 9; $j++) {
                HTMLtable += '<td id="' + (($i * 9) + $j) + '">' +
                        ((data[($i * 9) + $j] !== 0) ? data[($i * 9) +
                                $j] : '<input name="' + (($i * 9) + $j) +
                                '" type="text" pattern="[1-9]{1}" autocomplete="off"/>') +
                        '</td>';
            }
            HTMLtable += '</tr>';
        }
        HTMLtable += '</table></form>';
        grid.insertAdjacentHTML('beforeend', HTMLtable);
    }

    function clear() {
        document.getElementById('grid').innerHTML = '';
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