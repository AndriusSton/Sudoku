<?php
require 'C:\xampp\htdocs\Sudoku\classes\Algorithm.php';
require 'C:\xampp\htdocs\Sudoku\classes\Puzzle.php';

$puzzle = new Puzzle(new Algorithm(), 0);
$puzzleArray = $puzzle->getPuzzle();
?>

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sudoku</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"/>
        <link rel="stylesheet" type="text/css" href="style.css"/>
    </head>
    <body>
        <div class='container'>
            <div class='row'>
                <div class='col-lg-3'>
                    <button type="button" class='btn btn-primary' id='btn'>Give me a Sudoku</button>
                </div>
                <div id='grid' class='col-lg-6'></div>
                <div id='spacer' class='col-lg-3'></div>
            </div>
        </div>
        <script src="js/main.js"></script> 
    </body>
</html>
