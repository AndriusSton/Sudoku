# The Sudoku game project

## Grid generation
App generates 9x9 sudoku grid with a selected difficulty level. 
Grid generation is based on backtracking-like algorithm.
The grid generated is just an 81 size array of numbers 0-9.
The levels are hardcoded:
 * EASY (20 empty cells);
 * MEDIUM (40 empty cells);
 * HARD (50 empty cells).

 ## Endpoints
There are five endpoints/services. In depth docs of endpoints TBD.
* get_puzzle - returns a json of a grid;
* check - checks differences between initial grid and solution provided, returns array of wrong cell indexes;
* solve - takes an initial grid and returns a solved grid;
* get_pdf - returns a generated PDF doc based on sudoku grid settings: number of grids, difficulty level;
* save - *Under construction* - saves game effort to cookies.

## PDF doc generator
PDF document generation is based on **tecnickcom/tcpdf**

## Live project
https://game-sudoku.herokuapp.com

You can get grid via urls:
- get grid as array of 81 numbers: https://game-sudoku.herokuapp.com/get/array/level
- get grid as json of 9 arrays of 9 numbers which represent 9 rows: https://game-sudoku.herokuapp.com/get/json/level
- "0" represents empty cell