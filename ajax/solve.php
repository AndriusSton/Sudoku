<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../classes/Algorithm.php';

$sudokuSolver = new Algorithm();
if (!isset($_POST)) {
    echo json_encode(array('message' => 'Error occured, please try again'));
}

$grid = $_POST;

$solution = $sudokuSolver->solve($grid);
if ($solution != null) {
    echo json_encode($solution);
} else {
    echo json_encode(array('message' => 'Error occured, please try again'));
}


