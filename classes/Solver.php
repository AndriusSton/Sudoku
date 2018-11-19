<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Solver
 *
 * @author Andrius
 */
class Solver {

    public function getSolution($puzzle) {
        $solution = array();
        if (is_array($puzzle)) {

            $solution = $puzzle;
            $wrongChoices = array();
            $emptyCells = array();
            for ($j = 0; $j < 81; $j++) {

                if ($solution[$j] == 0) {

                    array_push($emptyCells, $j);
                    $possibleCellValues = $this->getPossibleValues($j, $solution);
                    if (empty($possibleCellValues)) {

                        do {
                            array_pop($emptyCells);
                            $j = end($emptyCells);

                            $possibleCellValues = $this->getPossibleValues($j, $solution);

                            if (array_key_exists($j, $wrongChoices)) {
                                array_push($wrongChoices[$j], $solution[$j]);
                            } else {
                                $wrongChoices[$j] = array($solution[$j]);
                            }

                            $solution[$j] = 0;

                            if ($j < max(array_keys($wrongChoices))) {
                                foreach (array_keys($wrongChoices) as $key) {
                                    if ($key > $j) {
                                        unset($wrongChoices[$key]);
                                    }
                                }
                            }

                            foreach ($wrongChoices[$j] as $choice) {
                                $possibleCellValues = $this->removeFromPossible($possibleCellValues, $choice);
                            }
                        } while (sizeof($possibleCellValues) < 1);
                    }
                } else {
                    continue;
                }

                $solution[$j] = $this->getRandomNumber($possibleCellValues);
            }
        }

        return $solution;
    }

    private function getPossibleValues($cell, $table) {
        $possibleCellValues = array(1, 2, 3, 4, 5, 6, 7, 8, 9);

        for ($i = 0; $i < 9; $i++) {
            $valueToUnset = $table[($i * 9 + $this->col($cell))];
            if (in_array($valueToUnset, $possibleCellValues)) {
                unset($possibleCellValues[array_keys($possibleCellValues, $valueToUnset)[0]]);
            }
        }

        for ($i = 0; $i < 9; $i++) {
            $valueToUnset = $table[$this->row($cell) * 9 + $i];
            if (in_array($valueToUnset, $possibleCellValues)) {
                unset($possibleCellValues[array_keys($possibleCellValues, $valueToUnset)[0]]);
            }
        }

        for ($i = 0; $i < 9; $i ++) {
            $valueToUnset = $table[floor($this->block($cell) / 3) * 27 + $i % 3 + 9 * floor($i / 3) + 3 * ($this->block($cell) % 3)];
            if (in_array($valueToUnset, $possibleCellValues)) {
                unset($possibleCellValues[array_keys($possibleCellValues, $valueToUnset)[0]]);
            }
        }

        sort($possibleCellValues);
        return $possibleCellValues;
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

    private function removeFromPossible($arr, $numberToRemove) {
        $modified = $arr;
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

    private function getRandomNumber($arr) {
        if (!empty($arr)) {
            return $arr[rand(0, sizeof($arr) - 1)];
        }
        return 0;
    }

}
