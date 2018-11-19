/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var generate_btn = document.getElementById('generate');
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


function renderGrid(data) {
    var HTMLtable = '<table>';
    for ($i = 0; $i < 9; $i++) {
        HTMLtable += '<tr id="' + $i + '">';
        for ($j = 0; $j < 9; $j++) {
            HTMLtable += '<td id="' + (($i * 9) + $j) + '">' +
                    ((data[($i * 9) + $j] !== 0) ? data[($i * 9) +
                            $j] : '<input id="f' + (($i * 9) + $j) +
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

