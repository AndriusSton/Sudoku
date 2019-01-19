<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../classes/Algorithm.php';

if (!isset($_POST)) {
    http_response_code(400);
}
if (!is_array($_POST)) {
    http_response_code(400);
}
$grid = $_POST;
$sudokuSolver = new Algorithm();
try {
    $solution = $sudokuSolver->solve($grid);
    echo json_encode(array('grid' => $solution));
} catch (Exception $ex) {
    echo json_encode(array('error' => $ex->getMessage()));
}