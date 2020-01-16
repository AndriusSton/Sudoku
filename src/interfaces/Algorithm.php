<?php

/**
 * Interface provides contract for getting a puzzle out of various algorithms
 * for solving and generating SUDOKU grids. 
 * 
 * @author Andrius Stonys
 */

namespace App\Interfaces;

interface Algorithm {
    /*
     * Solves a sudoku puzzle
     * $param Array $puzzle
     * @return Array
     */

    public function solve($puzzle);


    /*
     * Generates sudoku grid
     * @return Array
     */

    public function generate();
}
