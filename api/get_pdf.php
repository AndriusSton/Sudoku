<?php

include_once '../classes/Puzzle.php';
include_once '../classes/Algorithm.php';
include_once '../classes/PDFGenerator.php';


if (isset($_GET['num'])) {
    if (!filter_var($_GET['num'], FILTER_VALIDATE_INT) === false) {
        $numOfGrids = $_GET['num'];
        unset($_GET);
        if ($numOfGrids > 0 && $numOfGrids < 100) {

            // Instatiate puzzle object
            $puzzle = new Puzzle(new Algorithm());

            // Getting puzzle array
            $arrayOfPuzzles = array();
            for ($i = 0; $i < $numOfGrids; $i++) {
                $arrayOfPuzzles[] = $puzzle->getPuzzle('medium');
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
        echo json_encode(array('error' => 'Incorrect number of grids'));
    }
} else {
    echo json_encode(array('error' => 'Number of grids is not set'));
}


