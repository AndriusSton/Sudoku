<?php

/**
 * Algorithm class provides SUDOKU grid generation method using backtracking
 * algorithm. Each number is selected out of number of available choices at
 * particular cell location. If there is nothing to choose from, cell counter 
 * gets back to a previous cell which had more than one choice to select from.
 *
 * @author Andrius Stonys
 */
include_once '../interfaces/Algorithm.php';

class Backtracking implements Algorithm {
    /*
     * Initial sudoku grid[]. Empty cell is represented by value zero.
     */

    const INITIAL_TABLE = array(
        0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0
    );

    /*
     * Initial possible values for a cell
     */
    const INITIAL_POSSIBLE_VALUES = array(1, 2, 3, 4, 5, 6, 7, 8, 9);

    /*
     * Solves a sudoku puzzle and returns a full sudoku grid[]
     * @param Array $puzzle with a size of 81 elements
     * @return array $solution
     */

    public function solve($puzzle) {

        if (sizeof($puzzle) != 81) {
            throw new Exception('Solver: 9x9 sudoku is expected');
        }
        $solution = $puzzle;
        $wrongChoices = array();

        $emptyCells = array_keys($puzzle, 0);

        // Iterate through empty cells and randomly select the number
        // from possible cell value array
        for ($j = 0; $j < sizeof($emptyCells); $j++) {

            // Get possible values for the $j cell in a grid
            $possibleCellValues = self::getPossibleValues($emptyCells[$j], $solution);

            // IF there is nothing to choose, iterate the following steps until
            // the possible cell value array has at least 2 values to select
            // from, as one of these values will be the wrong one already 
            // selected at that cell
            if (empty($possibleCellValues)) {

                do {
                    // get back to previous cell index
                    $j--;

                    // get the previous cell values for that index
                    $possibleCellValues = self::getPossibleValues($emptyCells[$j], $solution);

                    // form an array of wrong choices at the cell
                    if (array_key_exists($emptyCells[$j], $wrongChoices)) {
                        array_push($wrongChoices[$emptyCells[$j]], $solution[$emptyCells[$j]]);
                    } else {
                        $wrongChoices[$emptyCells[$j]] = array($solution[$emptyCells[$j]]);
                    }

                    // asign the initial value at the observed cell
                    $solution[$emptyCells[$j]] = 0;

                    // Each time we iterate to a lower cell, we have to clean 
                    // up the wrong choices array from wrong higher cell values,
                    // as  they are no longer actual for future choices
                    // The last $j index observed in a loop is the highest 
                    // index in a wrong choices array
                    if ($emptyCells[$j] < max(array_keys($wrongChoices))) {
                        foreach (array_keys($wrongChoices) as $key) {
                            if ($key > $emptyCells[$j]) {
                                unset($wrongChoices[$key]);
                            }
                        }
                    }

                    // Remove each wrong choice from possible cell values array
                    // at the observed cell index
                    $possibleCellValues = array_diff($possibleCellValues, $wrongChoices[$emptyCells[$j]]);
                    sort($possibleCellValues);

                    // Do the steps while there is nothing to choose from
                } while (sizeof($possibleCellValues) < 1);
            }

            // Randomly select and asign a number from possible cell value
            // array
            $solution[$emptyCells[$j]] = $possibleCellValues[array_rand($possibleCellValues, 1)];
        }
        return $solution;
    }

    /*
     * Generates a Sudoku grid[]. generate() uses solve() method by passing
     * empty grid (INITIAL_TABLE).
     * @return array $solution
     */

    public function generate() {
        return self::solve(self::INITIAL_TABLE);
    }

    /*
     * Calculates possible choices for a cell value
     * @param int $cell
     * @param array $table
     * @return array $possible values
     */

    private function getPossibleValues($cell, $table) {
        $possibleValues = array_diff(
                self::INITIAL_POSSIBLE_VALUES, self::getRowArray($cell, $table), self::getColArray($cell, $table), self::getBlockArray($cell, $table));
        sort($possibleValues);
        return $possibleValues;
    }

    /*
     * Returns row number of the cell passed
     * @param int $cell
     * @return int
     */

    private function row($cell) {
        return (int) floor($cell / 9);
    }

    /*
     * Returns column number of the cell passed
     * @param int $cell
     * @return int
     */

    private function col($cell) {
        return (int) floor($cell % 9);
    }

    /*
     * Returns block number of the cell passed
     * @param int $cell
     * @return int
     */

    private function block($cell) {
        return floor(self::row($cell) / 3) * 3 + floor(self::col($cell) / 3);
    }

    /*
     * Returns an array of cells in a single row to which a passed $cell belongs
     * The method is not being used. For debug purpose only.
     * @param int $cell
     * @param array $table
     * @return array $cells
     */

    public function getRowArray($cell, $table) {
        $cells = array();
        for ($i = 0; $i < 9; $i++) {
            $index = $i * 9 + self::col($cell);
            $cells[$index] = $table[$index];
        }
        return $cells;
    }

    /*
     * Returns an array of cells in a single column to which a passed $cell belongs
     * The method is not being used. For debug purpose only.
     * @param int $cell
     * @param array $table
     * @return array $cells
     */

    public function getColArray($cell, $table) {
        $cells = array();
        for ($i = 0; $i < 9; $i++) {
            $index = self::row($cell) * 9 + $i;
            $cells[$index] = $table[$index];
        }
        return $cells;
    }

    /*
     * Returns an array of cells in a single block to which a passed $cell belongs
     * The method is not being used. For debug purpose only.
     * @param int $cell
     * @param array $table
     * @return array $cells
     */

    public function getBlockArray($cell, $table) {
        $cells = array();
        for ($i = 0; $i < 9; $i++) {
            $index = floor(self::block($cell) / 3) * 27 + $i % 3 + 9 * floor($i / 3) + 3 * (self::block($cell) % 3);
            $cells[$index] = $table[$index];
        }
        return $cells;
    }    
    
}
