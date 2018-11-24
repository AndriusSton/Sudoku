/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var generate_btn = document.getElementById('generate');
var submit_btn = document.getElementById('submit');
var solve_btn = document.getElementById('solve');
var grid = document.getElementById('grid');


generate_btn.addEventListener('click', function () {
    var url = 'http://localhost/Sudoku/api/get_puzzle.php';
    var request = new XMLHttpRequest();
    request.open('GET', url, true);
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

submit_btn.addEventListener('click', function () {
    var url = 'http://localhost/Sudoku/ajax/submit.php';
    var inputs = document.getElementsByTagName('input');
    var formData = new FormData();

    for (var i = 0; i < inputs.length; i++) {
        formData.append(inputs[i].name, inputs[i].value);
    }
    console.log(formData);
    var request = new XMLHttpRequest();
    request.open('POST', url, true);
    request.send(formData);

});

solve_btn.addEventListener('click', function () {
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

});

function renderGrid(data) {
    var HTMLtable = '<form action="ajax/submit.php" method="POST" id="solution"><table>';
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

