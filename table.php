<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of table
 *
 * @author Andrius
 */
class table {

    private $cells = array();
    private $table = array(
        0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0
    );

    public function row($cell) {
        return $cell / 9;
    }

    public function col($cell) {
        return $cell % 9;
    }

    public function block($cell) {
        return floor(row($cell) / 3) * 3 + floor(col($cell) / 3);
    }

    public function is_ok_in_row($number, $cell, $table) {
        $ok = true;
        $row = row($cell);
        for ($i = 0; $i < 9; $i++) {
            if ($table[$row * 9 + $i] === $number) {
                $ok = false;
                break;
            }
        }
        return $ok;
    }

    public function is_ok_in_col($number, $cell, $table) {
        $ok = true;
        $col = col($cell);
        for ($i = 0; $i < 9; $i++) {
            if ($table[$col * 9 + $i] === $number) {
                $ok = false;
                break;
            }
        }
        return $ok;
    }

    public function is_ok_in_block($number, $cell, $table) {
        $ok = true;
        $block = block($cell);
    }

    public static function generate() {

        // Number of cells in one line
        $cell_count = 9;

        // A table size is cell_count*cell_count
        $table = array();

        for ($i = 0; $i < $cell_count; $i++) {

            $row = array();
            for ($j = 0; $j < $cell_count; $j++) {

                $num_selected = false;
                $num = rand(1, 9);
                while (!$num_selected) {

                    if (empty($table) && empty($row)) {
                        $num_selected = true;
                    }

                    if (empty($table) && !empty($row)) {
                        if (!in_array($num, $row)) {
                            $num_selected = true;
                        } else {
                            $num = rand(1, 9);
                            continue;
                        }
                    }

                    if (!empty($table)) {

                        for ($k = 0; $k < sizeof($table); $k++) {

                            if ($table[$k][$j] == $num || in_array($num, $row)) {
                                $num_selected = false;
                                $num = rand(1, 9);
                                break;
                            } else {
                                $num_selected = true;
                            }
                        }
                    }
                }

                array_push($row, $num);
            }

            array_push($table, $row);
        }

        $col1 = array();
        $col2 = array();
        $col3 = array();
        echo '<pre>';
        var_dump(sizeof($table));
        for ($k = 0; $k < sizeof($table); $k++) {
            for ($l = 0; $l < sizeof($table); $l++) {
                if ($l == 0) {
                    array_push($col1, $table[$k][$l]);
                }

                if ($l == 1) {
                    array_push($col2, $table[$k][$l]);
                }

                if ($l == 2) {
                    array_push($col3, $table[$k][$l]);
                }
            }
        }
//        echo 'Column 2: ';
//        var_dump($col2);
//        echo '</pre>';
        return $table;
    }

}
