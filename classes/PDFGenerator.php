<?php

/**
 * PDFGenerator class uses TCPDF library to format and generate SUDOKU grids
 * in a PDF document.
 * 2 columns and 3 rows of SUDOKU puzzle tables per A4 format page.
 * 
 *
 * @author Andrius Stonys
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

// set font
        self::SetFont('helvetica', '', 12);
    }

    public function setPuzzleCollection(Array $puzzleCollection) {
        $this->puzzleCollection = $puzzleCollection;
    }

    /*
     * Renders a PDF document out of a $puzzleCollection.
     * 
     */
    
    public function renderPDF() {
        
        // Iterate through $puzzleCollection
        for ($i = 0; $i < sizeof($this->puzzleCollection); $i++) {

            // Add a page every sixth puzzle
            if ($i % 6 === 0) {
                self::addPage();
            }
            
            // Calculate offset for second column of puzzles
            $y = ($i > 1) ? self::getY() + 7 : self::getY();

            // Create a text to print out
            $txt = self::gridToHTML($this->puzzleCollection[$i]);
            
            // Switch between two columns to print $txt
            if ($i % 2 === 0) {
                parent::writeHTMLCell('80', '', '', $y, $txt, 0, 0, 1, true, 'J', true);
            } else {
                parent::writeHTMLCell('80', '', '', '', $txt, 0, 1, 1, true, 'J', true);
            }
        }
    }
    
    /*
     * Formats passed $grid[] as an HTML table and returns as a string.
     * 
     */

    private function gridToHTML($grid) {
        // HTML table styling and parent tag
        $HTMLtable = '<table style="border-collapse: collapse; border: 3px solid #000; width: 225px;">';
        
        // Create a 9x9 SUDOKU grid
        for ($i = 0; $i < 9; $i++) {
            $HTMLtable .= '<tr>';
            for ($j = 0; $j < 9; $j++) {
                // Every 3rd <td> border is thicker then the rest to represent
                // a classic SUDOKU grid formating
                $HTMLtable .= '<td style="height: 25px; border-right:  ' .
                        (($j + 1) % 3 === 0 ? '3' : '1') .
                        'px solid #000; border-bottom: ' .
                        (($i + 1) % 3 === 0 ? '3' : '1') .
                        'px solid #000; text-align: center">' .
                        (($grid[($i * 9) + $j] !== 0) ? $grid[($i * 9) + $j] : ' ') . '</td>';
            }
            $HTMLtable .= '</tr>';
        }
        // Closing tag
        $HTMLtable .= '</table>';
        return $HTMLtable;
    }

}
