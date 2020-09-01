<?php

/**
 * PDFGenerator class uses TCPDF library to format and generate SUDOKU grids
 * in a PDF document.
 * 2 columns and 3 rows of SUDOKU puzzle tables per A4 format page.
 * 
 *
 * @author Andrius Stonys
 */
namespace App\Service;

use TCPDF;

class PDFGenerator extends TCPDF {

    private $puzzleCollection;

    public function setFormatting() {
        // set header and footer fonts
        $this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
        $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
        $this->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Colors, line width and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');


// Color and font restoration
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(0);
        $this->SetFont('');

// set font
        $this->SetFont('helvetica', '', 12);
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
                $this->addPage();
            }
            
            // Calculate offset for second column of puzzles
            $y = ($i > 1) ? $this->getY() + 7 : $this->getY();

            // Create a text to print out
            $txt = $this->gridToHTML($this->puzzleCollection[$i]);
            
            // Switch between two columns to print $txt
            if ($i % 2 === 0) {
                $this->writeHTMLCell('80', '', '', $y, $txt, 0, 0, 1, true, 'J', true);
            } else {
                $this->writeHTMLCell('80', '', '', '', $txt, 0, 1, 1, true, 'J', true);
            }
        }
    }
    
    /*
     * Formats passed $grid[] as an HTML table and returns as a string.
     * 
     */

    private function gridToHTML($grid): string
    {
        // HTML table styling and parent tag
        $htmlTable = '<table style="border-collapse: collapse; border: 3px solid #000; width: 225px;">';
        
        // Create a 9x9 SUDOKU grid
        for ($i = 0; $i < 9; $i++) {
            $htmlTable .= '<tr>';
            for ($j = 0; $j < 9; $j++) {
                // Every 3rd <td> border is thicker then the rest to represent
                // a classic SUDOKU grid formating
                $htmlTable .= '<td style="height: 25px; border-right:  ' .
                        (($j + 1) % 3 === 0 ? '3' : '1') .
                        'px solid #000; border-bottom: ' .
                        (($i + 1) % 3 === 0 ? '3' : '1') .
                        'px solid #000; text-align: center">' .
                        (($grid[($i * 9) + $j] !== 0) ? $grid[($i * 9) + $j] : ' ') . '</td>';
            }
            $htmlTable .= '</tr>';
        }
        // Closing tag
        $htmlTable .= '</table>';
        return $htmlTable;
    }

}
