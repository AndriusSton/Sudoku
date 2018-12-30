<?php

/**
 * Description of PDFGenerator
 *
 * @author Andrius
 */
require_once('../config/tcpdf_config.php');
require_once('../tcpdf/tcpdf.php');

class PDFGenerator extends TCPDF {

    private $puzzle = null;
    private $layout;

    public function setLayout($layout) {
        $this->layout = $layout;
    }

    public function setPuzzle(Puzzle $puzzle) {
        $this->puzzle = $puzzle;
    }

    public function renderGrid($grid) {
        $cellDimensions = array(
            'width' => 8,
            'height' => 8
        );
        $fill = 0;
        for ($i = 0; $i < 9; $i++) {
            for ($j = 0; $j < 9; $j++) {
                $txt = ($grid[$i * 9 + $j] != 0) ? $grid[$i * 9 + $j] : '';
                $this->Cell($cellDimensions['width'], $cellDimensions['height'], $txt, 1, 0, 'L', $fill);
            }
            $this->Ln();
        }
    }

}
