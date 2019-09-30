var AppGlobals = {
    hostname: location.protocol + "//" + location.hostname + ((location.port) ? ":" + location.port : ""),
};

const Puzzle = {
    current: Array(),
    solution: false,
    increment_value: function (id) {
        this.current[id] = (this.current[id] === 9) ? 1 : ++this.current[id];
        document.getElementById(id).innerHTML = this.current[id];
        event.preventDefault();
    },
    render: function () {
        var HTMLtable = '<table>';
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
                        ((this.current[id] !== 0) ? this.current[id] :
                                '<button class="click" id="' + id + '" onclick="Puzzle.increment_value(this.id)">') +
                        '&nbsp;</button></td>';
            }
            HTMLtable += '</tr>';
        }
        HTMLtable += '</table>';
        document.getElementById('table').insertAdjacentHTML('beforeend', HTMLtable);
    }
};

const fetcher = {
    get: function (url = '') {
        return fetch(url)
                .then(res => {
                    return res.json();
                });
    },
    post: function (url = '', data = '') {
        return fetch(url, {
            method: 'POST',
            body: data
        }).then(res => {
            return res.json();
        });
    }
}

// Get and display new grid when the page is loaded
window.addEventListener('load', () => {
    fetcher.get(AppGlobals.hostname + '/services/get_puzzle/medium').then(result => {
        Puzzle.current = result.grid;
        sessionStorage.clear();
        // sessionStorage must be cleared on new grid request
        if (!sessionStorage.getItem('initial')) {
            // save the new grid to sessionStorage
            sessionStorage.setItem('initial', JSON.stringify(Puzzle.current));
        }
        clearTable();
        Puzzle.render();
        displayGrid();
    });
}, false);

/*
 * RESET BUTTON LISTENER
 * @returns {undefined}
 */
document.getElementById('reset-btn').addEventListener('click', function () {
    Puzzle.solution = false;
    Puzzle.current = JSON.parse(sessionStorage.getItem('initial'));
    clearTable();
    Puzzle.render();
});

/*
 * SOLVE BUTTON LISTENER
 * @returns {undefined}
 */
document.getElementById('solution-btn').addEventListener('click', function () {
    var url = AppGlobals.hostname + '/services/solve.php';
    var initial = JSON.parse(sessionStorage.getItem('initial'));
    var gridToSend = new FormData();
    for (var i = 0; i < initial.length; i++) {
        gridToSend.append(i, initial[i]);
    }

    fetcher.post(url, gridToSend).then(
            result => {
                Puzzle.solution = true;
                Puzzle.current = result.grid;
                clearTable();
                Puzzle.render();
                //displayGrid();
            }
    );
});

/*
 * CHECK BUTTON LISTENER
 * @returns {undefined}
 */
document.getElementById('check-btn').addEventListener('click', function () {
    var url = AppGlobals.hostname + '/services/check.php';
    var initial = JSON.parse(sessionStorage.getItem('initial'));
    if (!Puzzle.solution) {
        var gridToSend = new FormData();
        gridToSend.append('initial', JSON.stringify(initial));
        gridToSend.append('player_inputs', JSON.stringify(getPlayerInputs(initial, Puzzle.current)));
        fetcher.post(url, gridToSend)
                .then(result => {
                    displayWrongCells(result.wrong_cells);
                });
    } else {
        displayAlert('error', 'Nothing to solve.');
    }
});
/*
 * GET PDF BUTTON LISTENER
 * @returns {undefined}
 */
document.getElementById('get-pdf-btn').addEventListener('click', function () {
    var pdf_config = new FormData();
    var selector = document.getElementById('level');
    var level = selector[selector.selectedIndex].value;
    var numOfGrids = document.getElementById('num-of-grids').value;
    var url = AppGlobals.hostname + '/services/get_pdf.php';
    pdf_config.append('level', level);
    pdf_config.append('numOfGrids', numOfGrids);
// header: {content type : application/pdf} does not work, adobe cannot 
// render the file
    fetch(url, {
        method: 'POST',
        body: pdf_config
    }).then(res => res.blob())
            .then(blob => window.URL.createObjectURL(blob))
            .then(url => {
                var a = document.createElement('a');
                a.download = 'sudoku.pdf';
                a.href = url;
                document.body.appendChild(a);
                a.click();
            })
            .catch(error => console.error('Error:', error));
});

/*
 * SAVE BUTTON LISTENER
 * @returns {undefined}
 */
document.getElementById('save-btn').addEventListener('click', function () {
    var url = AppGlobals.hostname + '/services/save.php';
    var initial = JSON.parse(sessionStorage.getItem('initial'));
    if (!Puzzle.solution) {
        var gridToSend = new FormData();
        gridToSend.append('initial', JSON.stringify(initial));
        gridToSend.append('player_inputs', JSON.stringify(getPlayerInputs(initial, Puzzle.current)));
        fetcher.post(url, gridToSend)
                .then(result => {
                    displayAlert('', result['message']);
                });
    } else {
        displayAlert('error', 'Nothing to solve.');
    }
});

// Get the modal
var modal = document.getElementById("gameSave");

// Get the button that opens the modal
var btn = document.getElementById("save-btn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

var modalContent = document.getElementById("saveGameMsg");

// When the user clicks the button, open the modal 
btn.onclick = function () {
    modal.style.display = "block";
    var node = document.createElement("P");
    var textnode = document.createTextNode("We use cookies to save Your game effort");
    node.appendChild(textnode);
    modalContent.appendChild(node);
}

// When the user clicks on <span> (x), close the modal
span.onclick = function () {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// ------- FUNCTIONS -------

function getPlayerInputs(initial, solved) {
    var result = solved.map((item, index) => {
        if (item === 0) {
            return item;
        } else {
            return ((item - initial[index]) === 0) ? false : item - initial[index];
        }
    }).filter((val) => {
        return val !== false;
    });
    return result;
}

function requestNewGrid(level) {
    sessionStorage.clear();
    return fetch(AppGlobals.hostname + '/services/get_puzzle/' + level)
            .then(res => {
                return res.json();
            });
}

function getNewGrid(level) {
    requestNewGrid(level).then((result) => {
        Puzzle.current = result.grid;
        // sessionStorage must be cleared on new grid request
        if (!sessionStorage.getItem('initial')) {
            // save the new grid to sessionStorage
            sessionStorage.setItem('initial', JSON.stringify(Puzzle.current));
        }
        clearTable();
        Puzzle.render();
        displayGrid();
    });
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
    var cells = document.getElementsByClassName('click wrong');
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
 * Clears HTML table
 * @returns {undefined}
 */
function clearTable() {
    document.getElementById('table').innerHTML = '';
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
    document.getElementById('pdf-config').classList.remove('hidden');
    document.getElementById('grid').classList.add('hidden');
    clearTable();
    document.getElementById('main-container').classList.remove('hidden');


}