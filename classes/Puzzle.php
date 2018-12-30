<?php

/**
 * Puzzle class creates puzzle out of Sudoku grid. By creating a Puzzle object, 
 * an Algorithm class object have to be passed for grid generation. 
 *
 * @author Andrius
 */
include_once '../config/sudoku_defines.php';

class Puzzle {

    private $algorithm;
    private $level;

    public function __construct(Algorithm $sudoku) {
        $this->algorithm = $sudoku;
    }

    private function setLevel($level) {
        if (in_array($level, array_keys(SUDOKU_LEVELS))) {
            $this->level = SUDOKU_LEVELS[$level];
        } else {
            throw new Exception('Error: no such level defined');
        }
    }

    public function getPuzzle($level) {
        try {
            self::setLevel($level);
        } catch (Exception $ex) {
            $ex->getMessage();
        }

        try {
            $puzzle = $this->algorithm->generate();
        } catch (Exception $ex) {
            $ex->getMessage();
        }

        $removedCells = self::getRemovedCells($puzzle);
        for ($i = 0; $i < sizeof($puzzle); $i++) {
            if (in_array($i, $removedCells)) {
                $puzzle[$i] = 0;
            }
        }
        return $puzzle;
    }

    private function getRemovedCells($puzzle) {
        $removedCells = array();
        for ($i = 0; $i < $this->level; $i++) {
            $indexToRemove = rand(0, (sizeof($puzzle) - 1));
            while (in_array($indexToRemove, $removedCells)) {
                $indexToRemove = rand(0, (sizeof($puzzle) - 1));
            }
            $removedCells[$i] = $indexToRemove;
        }
        asort($removedCells);
        return $removedCells;
    }

}
