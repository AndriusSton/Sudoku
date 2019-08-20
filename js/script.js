var AppGlobals = {
    grid: '',
    solution: ''
};
// Get and display new grid when the page is loaded
window.addEventListener('load', function () {
    console.log('loaded');
    fetch('http://sudoku.game.com/services/get_puzzle/medium').then((response) => {
        return response.json();
    }).then((result) => {
        AppGlobals.grid = result.grid;
    }).then(() => {
        renderGrid(AppGlobals.grid);
        displayGrid();
    });
}, false)


// Listen for events

