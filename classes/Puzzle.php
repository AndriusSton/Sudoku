<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TableView
 *
 * @author Andrius
 */
class Puzzle {

    private $grid = array();
    private $puzzle = array();

    public function __construct(Algorithm $algorithm) {
        $this->grid = $algorithm->getGrid();
    }

    public function getPuzzle() {
        $this->puzzle = $this->grid;
        $removedCells = $this->getRemovedCells();
        for ($i = 0; $i < sizeof($this->grid); $i++) {
            if (in_array($i, $removedCells)) {
                $this->puzzle[$i] = 0;
            }
        }
        return $this->puzzle;
    }

    private function getRemovedCells() {
        $removedCells = array();
        for ($i = 0; $i < 40; $i++) {
            $indexToRemove = rand(0, 80);
            while (in_array($indexToRemove, $removedCells)) {
                $indexToRemove = rand(0, 80);
            }
            $removedCells[$i] = $indexToRemove;
        }
        asort($removedCells);
        return $removedCells;
    }

    
}
