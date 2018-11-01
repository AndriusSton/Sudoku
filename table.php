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
    private $possible = array(1, 2, 3, 4, 5, 6, 7, 8, 9);

    public function getPossible() {
        return $this->possible;
    }

    public function removeFromPossible($arr, $number_to_remove) {
        $modified = $arr;
        if (!in_array($number_to_remove, $modified)) {
            return $modified;
        }
        unset($modified[array_keys($modified, $number_to_remove)[0]]);
        sort($modified);
        return $modified;
    }

    public function getRandomNumber($arr) {
        if (!empty($arr)) {
            return $arr[rand(0, sizeof($arr) - 1)];
        }
        return 0;
    }

    public function getPossibleValues($cell, $table) {
        $possible_cell_values = $this->getPossible();
        if (empty($table) || empty($possible_cell_values)) {
            return $possible_cell_values;
        } else {
            if ($cell != 0) {

                for ($i = $this->row($cell); $i > 0; $i--) {
                    $value_to_unset = $table[($this->row($cell) - $i) * 9 + $this->col($cell)];
                    //echo 'Cell: ' . $cell . '<br/>';
                    //echo 'Value to remove: ' . $value_to_unset . '<br/>';
                    if (in_array($value_to_unset, $possible_cell_values)) {
                        unset($possible_cell_values[array_keys($possible_cell_values, $value_to_unset)[0]]);
                    }
                    sort($possible_cell_values);
                }
                for ($i = $this->col($cell); $i >= 0; $i--) {
                    $value_to_unset = $table[$this->row($cell) * 9 + $this->col($cell) - $i];
                    //echo 'Cell: ' . $cell . '<br/>';
                    //echo 'Value to remove: ' . $value_to_unset . '<br/>';
                    if (in_array($value_to_unset, $possible_cell_values)) {
                        unset($possible_cell_values[array_keys($possible_cell_values, $value_to_unset)[0]]);
                    }
                    sort($possible_cell_values);
                }

                for ($i = 0; $i < 9; $i ++) {
                    $value_to_unset = $table[floor($this->block($cell) / 3) * 27 + $i % 3 + 9 * floor($i / 3) + 3 * ($this->block($cell) % 3)];
                    if (in_array($value_to_unset, $possible_cell_values)) {
                        unset($possible_cell_values[array_keys($possible_cell_values, $value_to_unset)[0]]);
                    }
                    sort($possible_cell_values);
                }
            }
            //echo 'Possible values: ';
            //var_dump($possible_cell_values);
            //echo '<br/>';
            //echo 'Number of choises: ' . sizeof($possible_cell_values) . '<br/>';
            return $possible_cell_values;
        }
    }

    public function row($cell) {
        return (int) floor($cell / 9);
    }

    public function col($cell) {
        return (int) floor($cell % 9);
    }

    public function block($cell) {
        return floor($this->row($cell) / 3) * 3 + floor($this->col($cell) / 3);
    }

    public function is_ok_in_row($number, $cell, $table) {
        $ok = true;
        $row = $this->row($cell);
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
        $col = $this->col($cell);
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
        $block = $this->block($cell);

        for ($i = 0; $i < 9; $i++) {
            if ($table[floor($block / 3) * 27 + $i % 3 + 9 * floor($i / 3) + 3 * ($block % 3)] == $number) {
                $ok = false;
                break;
            }
        }
        return $ok;
    }

    public function is_unique($number, $cell, $table) {
        return $this->is_ok_in_row($number, $cell, $table) and $this->is_ok_in_col($number, $cell, $table) and $this->is_ok_in_block($number, $cell, $table);
    }

    public function getTable() {
        return $this->table;
    }

}
