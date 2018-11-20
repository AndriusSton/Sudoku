<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Algorithm class provides SUDOKU grid generation method using backtracking
 * algorithm. Each number is selected out of number of available choices at
 * particular cell. If there is nothing to choose from, cell counter gets back
 * to a previous cell which had more than one choice to select from.
 *
 * @author Andrius
 */
class Algorithm {

    
    /*
     * Initial sudoku grid. Empty cell is represented by value zero.
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
     * Returns the grid
     * 
     */
    public function getGrid() {
        return $this->generate();
    }
    
    /*
     * Returns row number of the cell passed
     * 
     */
    protected function row($cell) {
        return (int) floor($cell / 9);
    }

    /*
     * Returns column number of the cell passed
     * 
     */
    protected function col($cell) {
        return (int) floor($cell % 9);
    }

    /*
     * Returns block number of the cell passed
     * 
     */
    protected function block($cell) {
        return floor($this->row($cell) / 3) * 3 + floor($this->col($cell) / 3);
    }

    /*
     * Removes a passed value from a passed array and returns a result
     * 
     */
    protected function removeFromPossible($array, $numberToRemove) {
        $modified = $array;
        if (!in_array($numberToRemove, $modified)) {
            return $modified;
        }
        unset($modified[array_keys($modified, $numberToRemove)[0]]);
        sort($modified);
        return $modified;
    }

    /*
     * Returns a random number from an array passed
     * 
     */
    protected function getRandomNumber($array) {
        if (!empty($array) || is_null($array)) {
            return $array[rand(0, sizeof($array) - 1)];
        }
        throw new Exception('Empty array passed');
    }

    /*
     * Returns possible values for a cell passed in a passed table passed
     * 
     */
    private function getPossibleValues($cell, $table) {
        $possibleCellValues = self::INITIAL_POSSIBLE_VALUES;
        
        // IF an empty table is passed, initial possible cell value array
        // is returned
        // ELSE possible cell values are calculated
        if (empty($table)) {
            return $possibleCellValues;
        } else {
            // IF not a first cell of a grid
            if ($cell != 0) {

                // Check every cell in an upper rows from the given cell and
                // unset the number found from a possible cell value array
                for ($i = $this->row($cell); $i > 0; $i--) {
                    $valueToUnset = $table[($this->row($cell) - $i) * 9 + $this->col($cell)];
                    if (in_array($valueToUnset, $possibleCellValues)) {
                        unset($possibleCellValues[array_keys($possibleCellValues, $valueToUnset)[0]]);
                    }
                }
                
                // Check every cell in a previous columns from the given cell 
                // and unset the number found from a possible cell value array
                for ($i = $this->col($cell); $i >= 0; $i--) {
                    $valueToUnset = $table[$this->row($cell) * 9 + $this->col($cell) - $i];
                    if (in_array($valueToUnset, $possibleCellValues)) {
                        unset($possibleCellValues[array_keys($possibleCellValues, $valueToUnset)[0]]);
                    }
                }

                // Check every cell in a coresponding block (in which the given  
                // cell is) and unset the number found from a possible cell 
                // value array
                for ($i = 0; $i < 9; $i ++) {
                    $valueToUnset = $table[floor($this->block($cell) / 3) * 27 + $i % 3 + 9 * floor($i / 3) + 3 * ($this->block($cell) % 3)];
                    if (in_array($valueToUnset, $possibleCellValues)) {
                        unset($possibleCellValues[array_keys($possibleCellValues, $valueToUnset)[0]]);
                    }
                }
            }
            sort($possibleCellValues);
            return $possibleCellValues;
        }
    }

    /*
     * Generates and returns a sudoku grid
     * 
     */
    private function generate() {
        $cells = self::INITIAL_TABLE;
        $wrongChoices = array();

        // Iterate through all the 9x9 cell grid and randomly select the number
        // from possible cell value array
        for ($j = 0; $j < 81; $j++) {
            
            // Get possible values for the $j cell in a grid
            $possibleCellValues = $this->getPossibleValues($j, $cells);

            // IF there is nothing to choose, iterate the following steps until
            // the possible cell value array has at least 2 values to select
            // from, as one of these values will be the wrong one already 
            // selected at that cell
            if (empty($possibleCellValues)) {
                
                do {
                    // get back to previous cell index
                    $j--;
                    // get the previous cell values for that index
                    $possibleCellValues = $this->getPossibleValues($j, $cells);

                    // form an array of wrong choices at the cell
                    if (array_key_exists($j, $wrongChoices)) {
                        array_push($wrongChoices[$j], $cells[$j]);
                    } else {
                        $wrongChoices[$j] = array($cells[$j]);
                    }

                    // asign the initial value at the observed cell
                    $cells[$j] = 0;

                    // Each time we iterate to a lower cell, we have to clean 
                    // up the wrong choices array from wrong higher cell values,
                    // as  they are no longer actual for future choices
                    // The last $j index observed in a loop is the highest 
                    // index in a wrong choices array
                    if ($j < max(array_keys($wrongChoices))) {
                        foreach (array_keys($wrongChoices) as $key) {
                            if ($key > $j) {
                                unset($wrongChoices[$key]);
                            }
                        }
                    }

                    // Remove each wrong choice from possible cell values array
                    // at the observed cell index
                    foreach ($wrongChoices[$j] as $choice) {
                        $possibleCellValues = $this->removeFromPossible($possibleCellValues, $choice);
                    }
                    
                    // Do the steps while there is nothing to choose from
                } while (sizeof($possibleCellValues) < 1);
            }

            // Randomly select and asign a number from possible cell value
            // array
            $cells[$j] = $this->getRandomNumber($possibleCellValues);
        }
        return $cells;
    }
}
