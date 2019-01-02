<?php

include_once '../classes/Puzzle.php';
include_once '../classes/Algorithm.php';
include_once '../classes/PDFGenerator.php';



// Instatiate puzzle object
$puzzle = new Puzzle(new Algorithm());

// Getting puzzle array
$arrayofPuzzles = array();
for ($i = 0; $i < 9; $i++) {
    $arrayofPuzzles[] = $puzzle->getPuzzle('medium');
}
$puzzleArray = $puzzle->getPuzzle('medium');

// create new PDF document
$pdf = new PDFGenerator(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Colors, line width and bold font
$pdf->SetFillColor(255, 0, 0);
$pdf->SetTextColor(255);
$pdf->SetDrawColor(128, 0, 0);
$pdf->SetLineWidth(0.3);
$pdf->SetFont('', 'B');


// Color and font restoration
$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0);
$pdf->SetFont('');

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------
// set font
$pdf->SetFont('helvetica', '', 12);

// add a page
//$pdf->AddPage();
//$y = $pdf->getY();
//$txt = $pdf->gridToHTML($puzzleArray);
//$pdf->writeHTML($txt,true, false, true, false, '');
// print sudoku grid
//$pdf->renderPDF(111);
// ---------------------------------------------------------
// close and output PDF document


for ($i = 0; $i < 9; $i++) {


    if ($i % 6 === 0) {
        $pdf->addPage();
    }
    $y = ($i > 1)? $pdf->getY()+7 : $pdf->getY();


    $txt = $pdf->gridToHTML($arrayofPuzzles[$i]);
    if ($i % 2 === 0) {
        $pdf->writeHTMLCell('80', '', '', $y, $txt, 0, 0, 1, true, 'J', true);
    } else {
        $pdf->writeHTMLCell('80', '', '', '', $txt, 0, 1, 1, true, 'J', true);
    }
}

$pdf->Output('sudoku.pdf', 'I');
