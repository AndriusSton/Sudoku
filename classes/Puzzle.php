<?php

/**
 * Puzzle class creates puzzle out of Sudoku grid. By creating a Puzzle object, 
 * an Algorithm class object have to be passed for grid generation. 
 *
 * @author Andrius
 */
class Puzzle {

    private $algorithm;
    private $level;

    public function __construct(Algorithm $sudoku) {
        $this->algorithm = $sudoku;
    }

    private function setLevel($level) {
        switch ($level):
            case 1: $this->level = 20;
                break;
            case 2: $this->level = 40;
                break;
            case 3: $this->level = 50;
                break;
            default: $this->level = 40;
        endswitch;
    }

    public function getPuzzle($level = 1) {
        self::setLevel($level);
        $puzzle = $this->algorithm->generate();
        $removedCells = $this->getRemovedCells($puzzle);
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
