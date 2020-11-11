<?php

namespace App\Controller;

use App\Service\Backtracking;
use App\Service\PDFGenerator;
use App\Service\Puzzle;
use Core\Config;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of PostController
 *
 * @author Andrius
 */
class PostController
{
    public function solveAction(Request $request)
    {
        $grid = $request->request->all();

        $sudokuSolver = new Backtracking();
        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');
        try {
            $solution = $sudokuSolver->solve($grid);
            $response->setContent(
                json_encode(
                    [
                        'grid' => $solution,
                    ]
                )
            );
            $response->send();
        } catch (Exception $ex) {
            $response->setContent(
                json_encode(
                    [
                        'error' => $ex->getMessage(),
                    ]
                )
            );
            $response->send();
        }
    }

    public function checkAction(Request $request)
    {
        $initialGrid = array_map(
            'intval',
            json_decode($request->request->get('initial'), true)
        );
        $playerInputs = array_map(
            'intval',
            json_decode($request->request->get('player_inputs'), true)
        );
        if (!$initialGrid || !$playerInputs) {
            die('nothing to check');
        }

        $checker = new Puzzle(new Backtracking());
        $response = new Response();
        $response->headers->set('Content-Type', 'text/plain');
        try {
            $solved = $checker->check($initialGrid, $playerInputs);

            if (!empty($solved)) {
                $response->setContent(
                    json_encode(
                        [
                            'wrong_cells' => $solved,
                        ]
                    )
                );
            } else {
                $response->setContent(
                    json_encode(
                        [
                            'message' => 'Congratulations! :)',
                        ]
                    )
                );
            }
            $response->send();
        } catch (Exception $ex) {
            $response->setContent(
                json_encode(
                    [
                        'error' => $ex->getMessage(),
                    ]
                )
            );
            $response->send();
        }
    }

    public function pdfAction(Request $request)
    {
        $numOfGrids = (int)$request->request->get('numOfGrids');
        $level = $request->request->get('level');

        if ($numOfGrids > 0 && $numOfGrids <= Config::MAX_NUM_OF_GRIDS) {

            $algorithm = new Backtracking();
            $puzzle = new Puzzle($algorithm);

            // Generate $numOfGrids of SUDOKU grids
            $arrayOfPuzzles = array();
            for ($i = 0; $i < $numOfGrids; $i++) {
                $arrayOfPuzzles[] = $puzzle->getPuzzle($level);
            }

            // create new PDF document
            $pdf = new PDFGenerator(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->setFormatting();
            $pdf->setPuzzleCollection($arrayOfPuzzles);
            $pdf->renderPDF();
            $pdf->Output('sudoku.pdf', 'I');
        }
    }
}
