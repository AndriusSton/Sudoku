<?php

/**
 * Service for getting the PDF document.
 * 
 */

// Headers are set in $pdf->Output()

include_once '../classes/Puzzle.php';
include_once '../classes/Backtracking.php';
include_once '../classes/PDFGenerator.php';

// validate $_POST
if (!isset($_POST['level']) && !isset($_POST['numOfGrids'])) {
    http_response_code(400);
}

if (!in_array($_POST['level'], array_keys(SUDOKU_LEVELS))) {
    http_response_code(400);
}

$numOfGrids = (int) $_POST['numOfGrids'];
$level = $_POST['level'];
unset($_POST);

// the max number of grids available per PDF document is hardcoded to 100 grids
if ($numOfGrids > 0 && $numOfGrids < 101) {

    $puzzle = new Puzzle(new Backtracking());

// Generate $numOfGrids of SUDOKU grids
    $arrayOfPuzzles = array();
    for ($i = 0; $i < $numOfGrids; $i++) {
        $arrayOfPuzzles[] = $puzzle->getPuzzle($level);
    }

// create new PDF document
    $pdf = new PDFGenerator(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->setFormating();
    $pdf->setPuzzleCollection($arrayOfPuzzles);
    $pdf->renderPDF();
    $pdf->Output('sudoku.pdf', 'I');
}




