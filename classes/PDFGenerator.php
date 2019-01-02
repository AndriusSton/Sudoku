<?php

/**
 * Description of PDFGenerator
 *
 * @author Andrius
 */
require_once('../config/tcpdf_config.php');
require_once('../tcpdf/tcpdf.php');

class PDFGenerator extends TCPDF {

    public function renderPDF($numOfGrids) {

        for ($i = 0; $i < $numOfGrids; $i++) {
            if ($i % 6 === 0) {
                self::addPage();
                $offsetY = 0;
            }
            if ($i % 2 === 0) {
                self::Multicell((8 * 9), (8 * 9), 'First GRID', 1, 'L', 1, 1, 10, (($offsetY * 8 * 9) + 20 + ($offsetY * 10)), true, '', true);
            } else {
                $offsetY--;
                self::Multicell((8 * 9), (8 * 9), 'Second GRID', 1, 'L', 1, 1, (8 * 9 + 20), (($offsetY * 8 * 9) + 20 + ($offsetY * 10)), true, '', true);
            }
            $offsetY++;
        }
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

    public function gridToHTML($grid) {
        $HTMLtable = '<table style="border-collapse: collapse; border: 3px solid #000; width: 225px;">';
        for ($i = 0; $i < 9; $i++) {
            $HTMLtable .= '<tr>';
            for ($j = 0; $j < 9; $j++) {
                $HTMLtable .= '<td style="height: 25px; border-right:  ' . 
                        (($j+1)%3 === 0? '3' : '1') . 
                        'px solid #000; border-bottom: ' . 
                        (($i+1)%3 === 0? '3' : '1') . 
                        'px solid #000; text-align: center">' .
                (($grid[($i * 9) + $j] !== 0) ? $grid[($i * 9) +$j] : ' ') . '</td>';
            }
            $HTMLtable .= '</tr>';
        }
        $HTMLtable .= '</table>';
        return $HTMLtable;
    }

}
