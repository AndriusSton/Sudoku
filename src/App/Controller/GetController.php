<?php

namespace App\Controller;

use Core\Config;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Backtracking;
use App\Service\Puzzle;

/**
 * Description of GetController
 *
 * @author Andrius
 */
class GetController
{

    public function arrayAction($level = null)
    {
        if ($this->validateLevel($level)) {
            $response = new Response();

            $algorithm = new Backtracking();
            $puzzle = new Puzzle($algorithm);
            $grid = $puzzle->getPuzzle($level);
            $response->setContent(
                json_encode(
                    [
                        'grid' => $grid,
                    ]
                )
            );
            $response->headers->set('Content-Type', 'text/plain');
            $response->send();
        } else {
            die('no such level');
        }
    }

    public function jsonAction($level = null)
    {
        if ($this->validateLevel($level)) {
            $response = new JsonResponse();

            $algorithm = new Backtracking();
            $puzzle = new Puzzle($algorithm);

            $grid = $puzzle->getPuzzle($level);

            $json = array();

            for ($i = 0; $i < count($grid) / 9; $i++) {
                $row = array();
                for ($j = 0; $j < count($grid) / 9; $j++) {
                    $row[$j] = $grid[$i * 9 + $j];
                }
                $json[$i] = $row;
            }

            $response->setData(['grid' => $json]);
            $response->headers->set('Content-Type', 'text/plain');
            $response->send();
        } else {
            die('no such level');
        }
    }

    private function validateLevel($level = null): bool
    {
        return array_key_exists($level, Config::LEVELS);
    }
}
