<?php

/**
 * Description of PDFGenerator
 *
 * @author Andrius
 */
require_once('../tcpdf/config/tcpdf_config.php');
require_once('../tcpdf/tcpdf.php');

class PDFGenerator extends TCPDF {

    private $puzzle = null;
    private $layout;
    
    public function setLayout($layout){
        $this->layout = $layout;
    }
    
    public function setPuzzle(Puzzle $puzzle){
        $this->puzzle = $puzzle;
    }

    public function renderGrid($grid) {
// Colors, line width and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');

        $cellDimensions = array(
            'width' => 8,
            'height' => 8
        );
        $w = array(8);
        $h = $w[0];
// Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $fill = 0;
// Data
        
        for ($i = 0; $i < 9; $i++) {
            for ($j = 0; $j < 9; $j++) {
                $txt = ($grid[$i * 9 + $j] != 0) ? $grid[$i * 9 + $j] : '';
                $this->Cell($cellDimensions['width'], $cellDimensions['height'], $txt, 1, 0, 'L', $fill);
            }
            $this->Ln();
        }
    }
    

}


