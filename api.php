<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ticTacToe\src\Services\Move;
use ticTacToe\src\exception\GameAlreadyWonException;
use ticTacToe\src\exception\MoveNotPossibleException;
use ticTacToe\src\exception\BoardStateInvalidException;

$request = Request::createFromGlobals();
$content = $request->getContent();
$gameState = json_decode($content, true);
$response = new Response();

if ($request->getMethod() == 'POST') {
    if (isValidRequest($gameState)) {

        $move = new Move();

        try {
            $nextState = $move->makeMove($gameState['boardState'], $gameState['player']);
            $newMovePositionX = $nextState[1];
            $newMovePositionY = $nextState[0];
            $gameState['boardState'][$newMovePositionX][$newMovePositionY] = $gameState['player'];
            if ($move->isGameWon($gameState['boardState'], $gameState['player'])) {
                $gameState["state"] = $gameState['player'] . ' Wins !';
                $gameState["canMove"] = "False";
            } else {
                $gameState["state"] = '';
                $gameState["canMove"] = "True";
            }
            $response->setContent(json_encode($gameState));
        } catch (GameAlreadyWonException $e) {
            $gameState["state"] = $e->getMessage();
            $gameState["canMove"] = "False";
            $response->setContent(json_encode($gameState));
        } catch (MoveNotPossibleException $e) {
            $gameState["state"] = $e->getMessage();
            $gameState["canMove"] = "False";
            $response->setContent(json_encode($gameState));
        } catch (BoardStateInvalidException $e) {
            $gameState["state"] = $e->getMessage();
            $gameState["canMove"] = "False";
            $response->setContent(json_encode($gameState));
        }

        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/json');
        $response->send();
    } else {
        sendErrorResponse($response);
    }
} else {
    sendErrorResponse($response);
}



/**
 * @param Response $response
 */
function sendErrorResponse($response)
{
    $response->setContent(json_encode("Malformed Request"));
    $response->setStatusCode(500);
    $response->headers->set('Content-Type', 'application/json');
    $response->send();
}

/**
 * @param array $gameState
 * @return bool
 */
function isValidRequest($gameState)
{
    if (empty($gameState['boardState'])) {
        return false;
    }

    if (!is_array($gameState['boardState'])) {
        return false;
    }

    if (!isset($gameState['player'])) {
        return false;
    }

    if (isBoardStateValid($gameState['boardState']) && ($gameState['player'] == 'X' || $gameState['player'] == 'O')) {

        return true;
    }

    return false;
}

/**
 * @param array $boardState
 * @return bool
 */
function isBoardStateValid($boardState)
{
    $validValues = ['X', 'O', ""];

    $countOfRows =0;
    foreach ($boardState as $row) {
        $countOfRows++;

        $countOfColumns = 0;
        foreach ($row as $item) {
            $countOfColumns++;
            if (!in_array($item, $validValues)) {
                return false;
            }
        }
        if ($countOfColumns !=3) {
            return false;
        }
    }
    if ($countOfRows !=3) {
        return false;
    }

    return true;
}
