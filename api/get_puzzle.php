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
$puzzle = new Puzzle(new Algorithm());

// Getting puzzle array
$puzzleArray = $puzzle->getPuzzle($_GET['level']);

// JSON
echo json_encode($puzzleArray);

