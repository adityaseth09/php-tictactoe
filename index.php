<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ticTacToe\src\Services\Move;
use ticTacToe\src\exception\GameAlreadyWonException;
use ticTacToe\src\exception\MoveNotPossibleException;
use ticTacToe\src\exception\BoardStateInvalidException;

$request = Request::createFromGlobals();
$top = $request->get('top');
$middle = $request->get('middle');
$bottom = $request->get('bottom');
$player = $request->get('player');
$content = $request->getContent();

$gameState = json_decode($content, true);

$move = new Move();
$response = new Response();
try {
    $nextState = $move->makeMove($gameState['boardState'], $gameState['player']);
    $response->setContent(json_encode($nextState));
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
