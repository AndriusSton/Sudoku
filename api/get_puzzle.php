<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../classes/Puzzle.php';
include_once '../classes/Algorithm.php';

// Instatiate puzzle object
$puzzle = new Puzzle(new Algorithm(), 0);

// Getting puzzle array
$puzzleArray = $puzzle->getPuzzle();

// JSON
if($puzzleArray != null){
    echo json_encode($puzzleArray);
} else {
    echo json_encode(array('message'=> 'Error occured, please try again'));
}
