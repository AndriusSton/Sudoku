// Global variables
var AppGlobals = {
    grid: '',
    solution: '',
    hostname: location.protocol + "//" + location.hostname + ((location.port) ? ":" + location.port : ""),
    request: createXMLHttpRequestObject()
};

// Get and display new grid when the page is loaded
window.addEventListener('load', fetchGrid('medium'), false);

/*
 * RESET BUTTON LISTENER
 * @returns {undefined}
 */
document.getElementById('reset-btn').addEventListener('click', function () {
    clearGrid();
    AppGlobals.solution = '';
    renderGrid(JSON.parse(sessionStorage.getItem('initial')));
});

/*
 * SOLVE BUTTON LISTENER
 * @returns {undefined}
 */
document.getElementById('solution-btn').addEventListener('click', function () {
    var url = AppGlobals.hostname + "/services/solve.php";
    var initial = JSON.parse(sessionStorage.getItem('initial'));
    var gridToSend = new FormData();
    for (var i = 0; i < initial.length; i++) {
        gridToSend.append(i, initial[i]);
    }

    fetch(url, {
        method: 'POST',
        body: gridToSend,
    }).then(res => res.json())
            .then(result => {
                AppGlobals.solution = result.grid;
                clearGrid();
                renderGrid(AppGlobals.solution);
                displayGrid();
            })
            .catch(error => console.error('Error:', error));
}
);



/*
 * CHECK BUTTON LISTENER
 * @returns {undefined}
 */
document.getElementById('check-btn').addEventListener('click', function () {
    var url = AppGlobals.hostname + "/services/check.php";
    var initial = JSON.parse(sessionStorage.getItem('initial'));
    var player_inputs = AppGlobals.solution ? false : fetchInputs(initial, AppGlobals.grid);

    if (player_inputs) {
        var gridToSend = new FormData();
        gridToSend.append('initial', JSON.stringify(initial));
        gridToSend.append('solution', JSON.stringify(player_inputs));
        process(url, 'POST', gridToSend, 'Grid');
    } else {
        displayAlert('error', 'Nothing to solve.');
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
    var url = AppGlobals.hostname + "/services/get_pdf.php";
    formData.append('level', level);
    formData.append('numOfGrids', numOfGrids);
    process(url, 'POST', formData, 'PDF');
});