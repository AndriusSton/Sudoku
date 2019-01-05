<?php

include_once '../classes/Puzzle.php';
include_once '../classes/Algorithm.php';
include_once '../classes/PDFGenerator.php';


if (isset($_GET['num']) && isset($_GET['level'])) {
    if (!filter_var($_GET['num'], FILTER_VALIDATE_INT) === false && !in_array($_GET['level'], SUDOKU_LEVELS)) {
        $numOfGrids = $_GET['num'];
        $level = $_GET['level'];
        unset($_GET);
        if ($numOfGrids > 0 && $numOfGrids < 100) {

            // Instatiate puzzle object
            $puzzle = new Puzzle(new Algorithm());

            // Getting puzzle array
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
        } else {
            echo json_encode(array('error' => 'Incorrect number of grids'));
        }
    } else {
        echo json_encode(array('error' => 'Incorrect number of grids or level'));
    }
} else {
    echo json_encode(array('error' => 'Number of grids or level is not set'));
}


