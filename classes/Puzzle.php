<?php

/**
 * Puzzle class provides methods to create puzzle out of SUDOKU grid. 
 * A Puzzle is a SUDOKU grid with random empty cells
 * SUDOKU levels are defined in sudoku_defines.php There are three levels of 
 * difficulty:
 *  - EASY (20 empty cells in a puzzle);
 *  - MEDIUM (40 empty cells);
 *  - HARD (50 empty cells)
 *
 * @author Andrius Stonys
 */
include_once '../config/sudoku_defines.php';
include_once '../interfaces/Algorithm.php';

class Puzzle {

    private $algorithm;
    private $level;

    public function __construct(Algorithm $sudoku) {
        $this->algorithm = $sudoku;
    }

    /*
     * Level setter. Checks if a passed $level is defined.
     * Throws an exception if passed $level could not be found in a
     * SUDOKU_LEVELS array
     * @param string $level
     * @return void
     */

    private function setLevel($level) {
        if (in_array($level, array_keys(SUDOKU_LEVELS))) {
            $this->level = SUDOKU_LEVELS[$level];
        } else {
            throw new Exception('Error: no such level defined');
        }
    }

    /*
     * Generates a puzzle with a passed difficulty $level
     * @param string $level
     * @return array $puzzle
     */

    public function getPuzzle($level) {

        // Set and validate $level
        try {
            self::setLevel($level);
        } catch (Exception $ex) {
            $ex->getMessage();
        }

        // Generate a full SUDOKU grid using an injected $algorithm
        try {
            $puzzle = $this->algorithm->generate();
        } catch (Exception $ex) {
            $ex->getMessage();
        }

        // Get randomly removed SUDOKU grid indexes and put them to array
        $removedCells = self::getRemovedCells($puzzle);

        // Iterate through the SUDOKU grid and if indexes match, remove the
        // value (set to 0) from that index.
        for ($i = 0; $i < sizeof($puzzle); $i++) {
            if (in_array($i, $removedCells)) {
                $puzzle[$i] = 0;
            }
        }
        return $puzzle;
    }

    /*
     * Removes values from random cells in a $puzzle[]
     * Returns an array of removed indexes
     * @param array $puzzle
     * @return array $puzzle
     */

    private function getRemovedCells($puzzle) {
        $removedCells = array();

        // Loop the remove procedure as long as a $level (20, 40 or 50) will 
        // be reached
        for ($i = 0; $i < $this->level; $i++) {

            // generate until uniq index is generated
            do {
                $indexToRemove = rand(0, (sizeof($puzzle) - 1));
            } while (in_array($indexToRemove, $removedCells));
            // save the removed index
            $removedCells[$i] = $indexToRemove;
        }
        asort($removedCells);
        return $removedCells;
    }

}
