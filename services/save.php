<?php

/**
 * Service for saving player inputs to cookies
 */
// Headers

if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: POST");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../classes/Puzzle.php';
include_once '../classes/Backtracking.php';

if (!isset($_POST)) {
    http_response_code(400);
}
if (!is_array($_POST)) {
    http_response_code(400);
}
$initial = array_map('intval', json_decode($_POST['initial'], true));
$player_inputs = array_map('intval', json_decode($_POST['player_inputs'], true));
$cookie_name = 'com_game_sudoku';

// Generate play_id/$key and save to a cookie
// Save both palyer_inputs and initial grid under one play_id/key
// As player can play multiple puzzles
// Restrict available amount of saves, for example no more than 5 saves
// Let user to choose, which play_id to fetch from cookie

// Do not use serialize/unserialize (https://stackoverflow.com/questions/9032007/arrays-in-cookies-php)
if (isset($_COOKIE[$cookie_name])) {
    $cookie_value = json_decode($_COOKIE[$cookie_name], true);
    $key = key($cookie_value);
    if ($cookie_value[$key]['initial'] === $initial) {
        $cookie_value = array(
            $key => array('initial' => $cookie_value[$key]['initial'],
                'player_inputs' => $player_inputs)
        );
        setcookie($cookie_name, json_encode($cookie_value), time() + 180, "/"); // 3min
    } else {
        $new_key = time() . rand(1, 9);
        array_push($cookie_value, array(
            $new_key => array(
                'initial' => $initial,
                'player_inputs' => $player_inputs
            )
        ));
        setcookie($cookie_name, json_encode($cookie_value), time() + 180, "/"); // 3min
    }
} else {
    $cookie_value = array(
        time() . rand(1, 9) => array('initial' => $initial,
            'player_inputs' => $player_inputs)
    );
    setcookie($cookie_name, json_encode($cookie_value), time() + 180, "/"); // 3min
}
echo json_encode(array(
    'message' => 'Saved'
));


//try {
//    $solved = $checker->check($initial, $player_inputs);
//    
//    if (!empty($solved)) {
//        echo json_encode(array('wrong_cells' => $solved));
//    } else {
//        echo json_encode(array('message' => 'Congratulations! :)'));
//    }
//} catch (Exception $ex) {
//    echo json_encode(array('error' => $ex->getMessage()));
//}