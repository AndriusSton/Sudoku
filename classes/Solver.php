<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'Algorithm.php';
/**
 * Description of Solver
 *
 * @author Andrius
 */
class Solver extends Algorithm {

    
    
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
            return $solution;
        } else {
            throw new Exception('Passed paramater must be an array');
        }
    }

    public function solve($puzzle) {

        if (!is_array($puzzle)) {
            throw new Exception('Passed paramater must be an array');
        }
        $solution = $puzzle;
        $wrongChoices = array();
        $emptyCells = array_keys($puzzle, 0);

        for ($j = 0; $j < sizeof($emptyCells); $j++) {

            $possibleCellValues = $this->getPossibleValues($emptyCells[$j], $solution);
            if (empty($possibleCellValues)) {

                do {
                    $j--;
                    $possibleCellValues = $this->getPossibleValues($emptyCells[$j], $solution);
                    if (array_key_exists($emptyCells[$j], $wrongChoices)) {
                        array_push($wrongChoices[$emptyCells[$j]], $solution[$emptyCells[$j]]);
                    } else {
                        $wrongChoices[$emptyCells[$j]] = array($solution[$emptyCells[$j]]);
                    }

                    $solution[$emptyCells[$j]] = 0;
                    if ($emptyCells[$j] < max(array_keys($wrongChoices))) {
                        foreach (array_keys($wrongChoices) as $key) {
                            if ($key > $emptyCells[$j]) {
                                unset($wrongChoices[$key]);
                            }
                        }
                    }
                    foreach ($wrongChoices[$emptyCells[$j]] as $choice) {
                        $possibleCellValues = $this->removeFromPossible($possibleCellValues, $choice);
                    }
                } while (sizeof($possibleCellValues) < 1);
            }
            $solution[$emptyCells[$j]] = $this->getRandomNumber($possibleCellValues);
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

}
