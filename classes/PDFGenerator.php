<?php

/**
 * Description of PDFGenerator
 *
 * @author Andrius
 */
require_once('../config/tcpdf_config.php');
require_once('../tcpdf/tcpdf.php');

class PDFGenerator extends TCPDF {

    private $puzzleCollection;

    public function setFormating() {
        // set header and footer fonts
        self::setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        self::setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
        self::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        self::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        self::SetHeaderMargin(PDF_MARGIN_HEADER);
        self::SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
        self::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
        self::setImageScale(PDF_IMAGE_SCALE_RATIO);

// Colors, line width and bold font
        self::SetFillColor(255, 0, 0);
        self::SetTextColor(255);
        self::SetDrawColor(128, 0, 0);
        self::SetLineWidth(0.3);
        self::SetFont('', 'B');


// Color and font restoration
        self::SetFillColor(255, 255, 255);
        self::SetTextColor(0);
        self::SetFont('');

// set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

// ---------------------------------------------------------
// set font
        self::SetFont('helvetica', '', 12);
    }

    public function setPuzzleCollection(Array $puzzleCollection) {
        $this->puzzleCollection = $puzzleCollection;
    }

    public function renderPDF() {
        for ($i = 0; $i < sizeof($this->puzzleCollection); $i++) {

            if ($i % 6 === 0) {
                self::addPage();
            }
            $y = ($i > 1) ? self::getY() + 7 : self::getY();

            $txt = self::gridToHTML($this->puzzleCollection[$i]);
            if ($i % 2 === 0) {
                self::writeHTMLCell('80', '', '', $y, $txt, 0, 0, 1, true, 'J', true);
            } else {
                self::writeHTMLCell('80', '', '', '', $txt, 0, 1, 1, true, 'J', true);
            }
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

    private function gridToHTML($grid) {
        $HTMLtable = '<table style="border-collapse: collapse; border: 3px solid #000; width: 225px;">';
        for ($i = 0; $i < 9; $i++) {
            $HTMLtable .= '<tr>';
            for ($j = 0; $j < 9; $j++) {
                $HTMLtable .= '<td style="height: 25px; border-right:  ' .
                        (($j + 1) % 3 === 0 ? '3' : '1') .
                        'px solid #000; border-bottom: ' .
                        (($i + 1) % 3 === 0 ? '3' : '1') .
                        'px solid #000; text-align: center">' .
                        (($grid[($i * 9) + $j] !== 0) ? $grid[($i * 9) + $j] : ' ') . '</td>';
            }
            $HTMLtable .= '</tr>';
        }
        $HTMLtable .= '</table>';
        return $HTMLtable;
    }

}
