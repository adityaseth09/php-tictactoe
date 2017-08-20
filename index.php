<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ticTacToe\src\Services\Move;
use ticTacToe\src\Services\MoveApiHelper;
use ticTacToe\src\exception\GameAlreadyWonException;
use ticTacToe\src\exception\MoveNotPossibleException;
use ticTacToe\src\exception\BoardStateInvalidException;


$request = Request::createFromGlobals();
$player = $request->get('player');
$content = $request->getContent();

$gameState = json_decode($content, true);

$move = new Move();
$moveApiHelper = new MoveApiHelper();

$response = new Response();
try {
    $nextState = $move->makeMove($gameState['boardState'], $gameState['player']);
    print_r($nextState);
    $gameState = $move->updateBoardState([$nextState[0], $nextState [1]], $nextState[2]);

    $gameState["state"] = '';
    $response->setContent(json_encode($gameState));
} catch (GameAlreadyWonException $e) {
    $gameState["state"] = $e->getMessage();
    $response->setContent(json_encode($gameState));
} catch (MoveNotPossibleException $e) {
    $gameState["state"] = $e->getMessage();
    $response->setContent(json_encode($gameState));
} catch (BoardStateInvalidException $e) {
    $gameState["state"] = $e->getMessage();
    $response->setContent(json_encode($gameState));
}

$response->setStatusCode(200);
$response->headers->set('Content-Type', 'application/json');
$response->send();

