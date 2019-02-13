<?php
/**
 * Service for getting the SUDOKU grid solution. A JSON object is sent back.
 */


// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../classes/Backtracking.php';

if (!isset($_POST)) {
    http_response_code(400);
}
if (!is_array($_POST)) {
    http_response_code(400);
}
$grid = $_POST;
$sudokuSolver = new Backtracking();
try {
    $solution = $sudokuSolver->solve($grid);
    echo json_encode(array('grid' => $solution));
} catch (Exception $ex) {
    echo json_encode(array('error' => $ex->getMessage()));
}