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

// Restrict available amount of saves, for example no more than 5 saves
// Let user to choose, which play_id to fetch from cookie
// Check if cookie is already set
if (isset($_COOKIE[$cookie_name])) {
    // Do not use serialize/unserialize (https://stackoverflow.com/questions/9032007/arrays-in-cookies-php)
    $cookie_value = json_decode($_COOKIE[$cookie_name], true);

    // each game is saved as a key-value pair where key is uniq id/key and value
    // is an array of 'initial' grid and 'player_inputs'
    $keys = array_keys($cookie_value);

    // iterate through all ids and check if requested save is performed on an
    // 'initial' grid that is already saved
    // if so, then update 'player_inputs'
    foreach ($keys as $key) {
        if ($cookie_value[$key]['initial'] === $initial) {
            $cookie_value = array(
                $key => array('initial' => $cookie_value[$key]['initial'],
                    'player_inputs' => $player_inputs)
            );
            setcookie($cookie_name, json_encode($cookie_value), time() + 180, "/"); // 3min
        } else {
            continue;
        }
    }

    // if its a new game save, then generate a new id and save the data as new 
    // key-value pair to cookie_value array
    $new_key = time() . rand(1, 9);
    $cookie_value[$new_key] = array(
        'initial' => $initial,
        'player_inputs' => $player_inputs
    );
    setcookie($cookie_name, json_encode($cookie_value), time() + 180, "/"); // 3min
} else {
    // if there's no cookie with a cookie_name, save a new cookie
    $cookie_value = array(
        time() . rand(1, 9) => array('initial' => $initial,
            'player_inputs' => $player_inputs)
    );
    setcookie($cookie_name, json_encode($cookie_value), time() + 180, "/"); // 3min
}

// send a message of a succesfull save
echo json_encode(array(
    'message' => 'Saved',
));
