<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../classes/Algorithm.php';

if (isset($_POST)) {
    if (is_array($_POST)) {
        $grid = $_POST;
        $sudokuSolver = new Algorithm();
        try {
            $solution = $sudokuSolver->solve($grid);
            echo json_encode(array('grid' => $solution));
        } catch (Exception $ex) {
            echo json_encode(array('error' => $ex->getMessage()));
        }
    } else {
        echo json_encode(array('error' => 'Wrong request'));
    }
} else {
    
    echo json_encode(array('error' => 'Wrong request'));
}







