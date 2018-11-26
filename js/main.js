
document.getElementById('generate').addEventListener('click', function () {
    var url = 'http://localhost/Sudoku/api/get_puzzle.php';
    var selector = document.getElementById('level');
    var level = selector[selector.selectedIndex].value;
    var request = new XMLHttpRequest();
    request.open('GET', url + '?level=' + level, true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.onreadystatechange = function () {
        if (request.readyState === XMLHttpRequest.DONE) {
            if (request.status >= 200 && request.status < 400) {
                var responseText = JSON.parse(request.responseText);
                clear();
                renderGrid(responseText);
            } else {
                console.log("Error returned");
            }
        }
    };
    request.send();
});

document.getElementById('solve').addEventListener('click', function () {
    var url = 'http://localhost/Sudoku/ajax/solve.php';
    var cells = document.getElementsByTagName('td');
    var formData = new FormData();

    for (var i = 0; i < cells.length; i++) {
        if (cells[i].getElementsByTagName('input').length > 0) {
            formData.append(cells[i].id, 0);
        } else {
            formData.append(cells[i].id, cells[i].innerHTML);
        }

    }
    var request = new XMLHttpRequest();
    request.open('POST', url, true);
    request.send(formData);

    request.onreadystatechange = function () {
        if (request.readyState === XMLHttpRequest.DONE) {
            if (request.status >= 200 && request.status < 400) {
                var responseText = JSON.parse(request.responseText);
                clear();
                renderGrid(responseText);
            } else {
                console.log("Error returned");
            }
        }
    };

});

function renderGrid(data) {
    var grid = document.getElementById('grid');
    var HTMLtable = '<table>';
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
    HTMLtable += '</table>';
    grid.insertAdjacentHTML('beforeend', HTMLtable);
}

function clear() {
    document.getElementById('grid').innerHTML = '';
}

