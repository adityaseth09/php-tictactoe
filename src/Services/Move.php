<?php
namespace ticTacToe\src\Services;

require __DIR__ . '/../../vendor/autoload.php';

use ticTacToe\src\exception\BoardStateInvalidException;
use ticTacToe\src\exception\GameAlreadyWonException;
use ticTacToe\src\exception\MoveNotPossibleException;

/**
 * Class Move
 * @package ticTacToe\src\Services
 */
Class Move implements MoveInterface
{

    const PLAYER_X = 'X';
    const PLAYER_O = 'O';
    const VACANT_AREA = '';
    const WIN_MESSAGE = " Wins The Game !!!";
    /**
     * Winning combination
     *
     * @const array
     */
    const WINNING_COMBINATIONS = [
        [1, 2, 3],
        [4, 5, 6],
        [7, 8, 9],
        [1, 4, 7],
        [2, 5, 8],
        [3, 6, 9],
        [1, 5, 9],
        [3, 5, 7],
    ];
    const CORNERS = [1, 3, 7, 9];
    const CENTER = 5;
    const SIDE = [2, 4, 6, 8];

    /** @var array */
    private $board = [
        1 => '',
        2 => '',
        3 => '',
        4 => '',
        5 => '',
        6 => '',
        7 => '',
        8 => '',
        9 => ''
    ];

    private $coordinates = [
        1 => [0, 0],
        2 => [1, 0],
        3 => [2, 0],
        4 => [0, 1],
        5 => [1, 1],
        6 => [2, 1],
        7 => [0, 2],
        8 => [1, 2],
        9 => [2, 2],
    ];

    /** @var string */
    private $humanPlayer;

    /** @var string */
    private $systemPlayer;

    /** @var int */
    private $countX;

    /** @var int */
    private $countO;

    /**
     * @inheritdoc
     */
    public function makeMove($boardState, $systemPlayerUnit = Move::PLAYER_X)
    {
        $this->setPlayers($systemPlayerUnit);
        if ($this->isGameWon($boardState, $this->humanPlayer)) {
            throw new GameAlreadyWonException($this->humanPlayer . MOVE::WIN_MESSAGE);
        } elseif ($this->isGameWon($boardState, $this->systemPlayer)) {
            throw new GameAlreadyWonException($this->systemPlayer . MOVE::WIN_MESSAGE);
        }

        if ($this->canMove($systemPlayerUnit)) {
            $nextMoveCoordinates = $this->coordinates[$this->makeNextMove()];

            return [$nextMoveCoordinates[0], $nextMoveCoordinates[1], $systemPlayerUnit];
        }
    }

    /**
     * @param $systemPlayerUnit
     * @return bool
     * @throws BoardStateInvalidException
     */
    private function canMove($systemPlayerUnit)
    {
        if ($systemPlayerUnit == Move::PLAYER_X) {
            $differenceOfMoves = $this->countX - $this->countO;
        } else {
            $differenceOfMoves = $this->countO - $this->countX;
        }
        if ($differenceOfMoves == -1 || $differenceOfMoves == 0) {
            return true;
        }

        throw new BoardStateInvalidException('The Count of Inputs are not valid');
    }

    /**
     * @param $playerUnit
     * @return bool
     */
    public function isGameWon($boardState, $playerUnit)
    {
        $this->setBoard($boardState, $playerUnit);
        foreach (Move::WINNING_COMBINATIONS as $combination) {
            if (
                $this->board[$combination[0]] == $playerUnit &&
                $this->board[$combination[1]] == $playerUnit &&
                $this->board[$combination[2]] == $playerUnit
            ) {
                return true;
            }
        }
        return false;
    }

    public function updateBoardState($newCoordinate, $playerUnit)
    {
        foreach ($this->coordinates as $index => $coordinate) {
            if ($coordinate == $newCoordinate) {
                $this->board[$index] = $playerUnit;

                return $this->board;
            }
        }
    }

    /**
     * @return int
     * @throws MoveNotPossibleException
     */
    private function makeNextMove()
    {
        $winningPosition = $this->getWinningMove($this->systemPlayer);
        if ($winningPosition) {
            $this->board[$winningPosition] = $this->systemPlayer;

            return $winningPosition;
        }

        $blockingPosition = $this->getWinningMove($this->humanPlayer);
        if ($blockingPosition) {
            $this->board[$blockingPosition] = $this->systemPlayer;

            return $blockingPosition;
        }

        $position = $this->makeCornerMove();
        if ($position) {
            return $position;
        }

        $position = $this->makeSideMove();
        if ($position) {
            return $position;
        }

        $position = $this->makeCenterMove();
        if ($position) {
            return $position;
        }

        throw new MoveNotPossibleException();
    }

    /**
     * @param $position
     * @return bool
     */
    private function move($position)
    {
        if ($this->board[$position] === '') {
            $this->board[$position] = $this->systemPlayer;

            return $position;
        }
    }

    /**
     * @return bool|mixed
     */
    private function makeCornerMove()
    {
        foreach (Move::CORNERS as $cornerPosition) {
            if ($this->move($cornerPosition)) {

                return $cornerPosition;
            }
        }
    }

    /**
     * @return bool|mixed
     */
    private function makeSideMove()
    {
        foreach (Move::SIDE as $sidePosition) {
            if ($this->move($sidePosition)) {

                return $sidePosition;
            }
        }
    }

    /**
     * @return bool|int
     */
    private function makeCenterMove()
    {
        if ($this->move(Move::CENTER)) {

            return Move::CENTER;
        }
    }

    /**
     * @param $playerUnit
     * @return bool|int
     */
    private function getWinningMove($playerUnit)
    {
        $winningPosition = 0;
        foreach (Move::WINNING_COMBINATIONS as $combination) {
            $winSteps = 0;
            $vacantSpots = 0;
            foreach ($combination as $position) {
                if ($this->board[$position] == $playerUnit) {
                    $winSteps++;
                } elseif ($this->board[$position] == '') {
                    ++$vacantSpots;
                    $winningPosition = $position;
                }
            }
            if ($winSteps == 2 && $vacantSpots == 1 && $winningPosition > 0) {

                return $winningPosition;
            }
        }
    }

    /**
     * @param $boardState
     * @param $playerUnit
     * @return bool
     *
     * @throws BoardStateInvalidException
     */
    private function setBoard($boardState, $playerUnit)
    {
        $i = 1;
        $this->countO = 0;
        $this->countX = 0;
        foreach ($boardState as $boardLine) {
            foreach ($boardLine as $value) {

                if (!($value == Move::PLAYER_X || $value == Move::PLAYER_O || $value == Move::VACANT_AREA)) {
                    throw new BoardStateInvalidException('The value ' . $value . ' is not valid');
                }
                if ($value == Move::PLAYER_X) {
                    $this->countX++;
                }
                if ($value == Move::PLAYER_O) {
                    $this->countO++;
                }
                $this->board[$i] = $value;
                $i++;
            }
        }
        $diff = $this->countX - $this->countO;
        if ($diff < -1 || $diff > 1) {
            throw new BoardStateInvalidException('The Count of Inputs are not valid');
        }
    }

    /**
     * @param $systemPlayerUnit
     */
    private function setPlayers($systemPlayerUnit)
    {
        $this->systemPlayer = $systemPlayerUnit;
        if ($systemPlayerUnit == Move::PLAYER_X) {
            $this->humanPlayer = Move::PLAYER_O;
        } else {
            $this->humanPlayer = Move::PLAYER_X;
        }
    }
}
