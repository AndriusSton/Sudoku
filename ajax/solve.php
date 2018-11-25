<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../classes/Algorithm.php';

$sudokuSolver = new Algorithm();
$solution = $sudokuSolver->solve($_POST);

if ($solution != null) {
    echo json_encode($solution);
} else {
    echo json_encode(array('message' => 'Error occured, please try again'));
}

