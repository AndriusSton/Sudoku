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
namespace App\Classes;

use App\Interfaces\Algorithm;


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

    /*
     * Aligns initial puzzle array with user solution array
     * Returns combined array
     * @param array $puzzle
     * @param array $solution
     * @return array $solved
     */
    public function align($puzzle, $solution) {
        if(sizeof($puzzle) != 81){
            throw new Exception('Checker: 9x9 sudoku is expected');
        }
        $aligned = array();
        $j = 0;
        for ($i = 0; $i < sizeof($puzzle); $i++) {
            if ($puzzle[$i] === 0) {
                    $aligned[$i] = $solution[$j];      
                $j++;
            }
        }
        return $aligned;
    }
    
    public function check($puzzle, $solution) {
        $solved = array_diff_assoc($this->algorithm->solve($puzzle), $puzzle);
        $aligned = self::align($puzzle, $solution);
        $dif = array_keys(array_diff_assoc($solved, $aligned));
        //var_dump($dif);
        return $dif;
    }
    

    public function getSelectedColumnArray($colIndex, $table) {
        $cells = array();
        for ($i = 0; $i < 9; $i++) {
            $index = $i * 9 + $colIndex;
            $cells[$index] = $table[$index];
        }
        return $cells;
    }

    public function getSelectedRowArray($rowIndex, $table) {
        $cells = array();
        for ($i = 0; $i < 9; $i++) {
            $index = $rowIndex * 9 + $i;
            $cells[$index] = $table[$index];
        }
        return $cells;
    }

    public function getSelectedBlockArray($blockIndex, $table) {
        $cells = array();
        for ($i = 0; $i < 9; $i++) {
            $index = floor($blockIndex / 3) * 27 + $i % 3 + 9 * floor($i / 3) + 3 * ($blockIndex % 3);
            $cells[$index] = $table[$index];
        }
        return $cells;
    }

    
    
    public function checkSums($table) {

//        if (array_sum($table) != 405) {
//            return false;
//        }

        // check blocks
        echo 'BLOCKS: ';
        for ($i = 0; $i < (sizeof($table) / 9); $i++) {
            if (array_sum(self::getSelectedBlockArray($i, $table)) != 45) {
                //return false;
                // TODO: analyze the block/ get duplicates
                echo 'Block no.: ' . $i;
                var_dump(self::getDuplicates(self::getSelectedBlockArray($i, $table)));
                
            }
        }

        // check columns
        echo 'COLUMNS: ';
        for ($i = 0; $i < (sizeof($table) / 9); $i++) {
            if (array_sum(self::getSelectedColumnArray($i, $table)) != 45) {
                //return false;
                // TODO: analyze the column/ get duplicates
                echo 'Column no.: ' . $i;
                var_dump(self::getDuplicates(self::getSelectedColumnArray($i, $table)));
            }
        }

        // check rows
        echo 'ROWS: ';
        for ($i = 0; $i < (sizeof($table) / 9); $i++) {
            if (array_sum(self::getSelectedRowArray($i, $table)) != 45) {
                //return false;
                // TODO: analyze the row/ get duplicates
                echo 'Row no.: ' . $i;
                var_dump(self::getDuplicates(self::getSelectedRowArray($i, $table)));
            }
        }

        return true;
    }
    
    public function getDuplicates($array){
        $unique = array_unique($array);
        return array_diff_assoc($array, $unique);
    }
}
