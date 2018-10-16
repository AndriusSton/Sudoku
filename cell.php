<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cell
 *
 * @author Andrius
 */
class cell {

    private $row;
    private $col;
    private $value;
    
    public function __construct($row, $col, $value) {
        $this->row = $row;
        $this->col = $col;
        $this->value = $value;
    }
    
    public function getRow() {
        return $this->row;
    }

    public function getCol() {
        return $this->col;
    }

    public function getValue() {
        return $this->value;
    }
    
    public function setValue($value){
        $this->value = $value;
    }
    
    

}
