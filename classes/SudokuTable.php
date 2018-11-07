<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Sudoku table class
 *
 * @author Andrius
 */
class SudokuTable {

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
    private function row($cell) {
        return (int) floor($cell / 9);
    }

    /*
     * Returns column number of the cell passed
     * 
     */
    private function col($cell) {
        return (int) floor($cell % 9);
    }

    /*
     * Returns block number of the cell passed
     * 
     */
    private function block($cell) {
        return floor($this->row($cell) / 3) * 3 + floor($this->col($cell) / 3);
    }

    /*
     * Removes a passed value from a passed array and returns a result
     * 
     */
    private function removeFromPossible($arr, $number_to_remove) {
        $modified = $arr;
        if (!in_array($number_to_remove, $modified)) {
            return $modified;
        }
        unset($modified[array_keys($modified, $number_to_remove)[0]]);
        sort($modified);
        return $modified;
    }

    /*
     * Returns a random number from an array passed
     * 
     */
    private function getRandomNumber($arr) {
        if (!empty($arr)) {
            return $arr[rand(0, sizeof($arr) - 1)];
        }
        return 0;
    }

    /*
     * Returns possible values for a cell passed in a passed table passed
     * 
     */
    private function getPossibleValues($cell, $table) {
        $possible_cell_values = self::INITIAL_POSSIBLE_VALUES;
        
        // IF an empty table is passed, initial possible cell value array
        // is returned
        // ELSE possible cell values are calculated
        if (empty($table)) {
            return $possible_cell_values;
        } else {
            // IF not a first cell of a grid
            if ($cell != 0) {

                // Check every cell in an upper rows from the given cell and
                // unset the number found from a possible cell value array
                for ($i = $this->row($cell); $i > 0; $i--) {
                    $value_to_unset = $table[($this->row($cell) - $i) * 9 + $this->col($cell)];
                    if (in_array($value_to_unset, $possible_cell_values)) {
                        unset($possible_cell_values[array_keys($possible_cell_values, $value_to_unset)[0]]);
                    }
                }
                
                // Check every cell in a previous columns from the given cell 
                // and unset the number found from a possible cell value array
                for ($i = $this->col($cell); $i >= 0; $i--) {
                    $value_to_unset = $table[$this->row($cell) * 9 + $this->col($cell) - $i];
                    if (in_array($value_to_unset, $possible_cell_values)) {
                        unset($possible_cell_values[array_keys($possible_cell_values, $value_to_unset)[0]]);
                    }
                }

                // Check every cell in a coresponding block (in which the given  
                // cell is) and unset the number found from a possible cell 
                // value array
                for ($i = 0; $i < 9; $i ++) {
                    $value_to_unset = $table[floor($this->block($cell) / 3) * 27 + $i % 3 + 9 * floor($i / 3) + 3 * ($this->block($cell) % 3)];
                    if (in_array($value_to_unset, $possible_cell_values)) {
                        unset($possible_cell_values[array_keys($possible_cell_values, $value_to_unset)[0]]);
                    }
                }
            }
            sort($possible_cell_values);
            return $possible_cell_values;
        }
    }

    /*
     * Generates and returns a sudoku grid
     * 
     */
    private function generate() {
        $cells = self::INITIAL_TABLE;
        $wrong_choices = array();

        // Iterate through all the 9x9 cell grid and randomly select the number
        // from possible cell value array
        for ($j = 0; $j < 81; $j++) {
            
            // Get possible values for the $j cell in a grid
            $possible_cell_values = $this->getPossibleValues($j, $cells);

            // IF there is nothing to choose, iterate the following steps until
            // the possible cell value array has at least 2 values to select
            // from, as one of these values will be the wrong one already 
            // selected at that cell
            if (empty($possible_cell_values)) {
                
                do {
                    // get back to previous cell index
                    $j--;
                    // get the previous cell values for that index
                    $possible_cell_values = $this->getPossibleValues($j, $cells);

                    // form an array of wrong choices at the cell
                    if (array_key_exists($j, $wrong_choices)) {
                        array_push($wrong_choices[$j], $cells[$j]);
                    } else {
                        $wrong_choices[$j] = array($cells[$j]);
                    }

                    // asign the initial value at the observed cell
                    $cells[$j] = 0;

                    // Each time we iterate to a lower cell, we have to clean 
                    // up the wrong choices array from wrong higher cell values,
                    // as  they are no longer actual for future choices
                    // The last $j index observed in a loop is the highest 
                    // index in a wrong choices array
                    if ($j < max(array_keys($wrong_choices))) {
                        foreach (array_keys($wrong_choices) as $key) {
                            if ($key > $j) {
                                unset($wrong_choices[$key]);
                            }
                        }
                    }

                    // Remove each wrong choice from possible cell values array
                    // at the observed cell index
                    foreach ($wrong_choices[$j] as $choice) {
                        $possible_cell_values = $this->removeFromPossible($possible_cell_values, $choice);
                    }
                    
                    // Do the steps while there is nothing to choose from
                } while (sizeof($possible_cell_values) < 1);
            }

            // Randomly select and asign a number from possible cell value
            // array
            $cells[$j] = $this->getRandomNumber($possible_cell_values);
        }
        return $cells;
    }
}
