<?php
namespace ticTacToe\src\Services;

require __DIR__ . '/../../vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use ticTacToe\src\exception\BoardStateInvalidException;

class MoveTest extends TestCase
{
    /** @var Move */
    private $move;

    public function setup()
    {
        $this->move = new Move();
    }


    /**
     * @dataProvider winningDataProvider
     *
     * @param $boardState
     * @param $playerUnit
     * @param $expected
     */
    public function testWinningMove($boardState, $playerUnit, $expected)
    {
        $this->assertEquals($expected, $this->move->makeMove($boardState, $playerUnit));
    }

    public function winningDataProvider()
    {
        return [
            "Winning Move Sides" =>[
                [
                    [ 'X', 'X', ''],
                    [ 'O', 'O', ''],
                    [ '' , '' , '']
                ], 'O', [2,1,'O']
            ],
            "Winning Move Top Bottom" =>[
                [
                    [ 'X', 'O', ''],
                    [ 'X', 'O', ''],
                    [ '' , '' , '']
                ], 'O', [1,2,'O']],
            "Winning Move Slanting " =>[
                [
                    [ 'X', 'O', ''],
                    [ 'O', 'X', ''],
                    [ '' , '' , '']
                ], 'O', [2,2,'O']
            ],
            "Winning Move Slanting 2" =>[
                [
                    [ 'O', 'O', 'X'],
                    [ 'O', 'X', ''],
                    [ '' , '' , '']
                ], 'X', [0,2,'X']
            ],
            "Blocking Move Sides" =>[
                [
                    [ 'X', '', ''],
                    [ 'O', 'O', ''],
                    [ '' , '' , '']
                ], 'X', [2,1,'X']
            ],
            "Blocking Move Top Bottom" =>[
                [
                    [ 'O', 'X', ''],
                    [ 'O', '', ''],
                    [ '' , '' , 'X']
                ], 'O', [0,2,'O']
            ],
            "First Move comes on Top Left Corner" => [
                [
                    [ '', '', ''],
                    [ '', '', ''],
                    [ '' , '' , '']
                ], 'O', [0,0,'O']
            ],
            "Second Move comes on Top Right Corner" => [
                [
                    [ 'O', '', ''],
                    [ '', '', ''],
                    [ '' , '' , '']
                ], 'X', [2,0,'X']
            ],
            "Move comes on Bottom Left Corner" => [
                [[ 'O', '', 'X'],
                    [ '', '', ''],
                    [ '' , 'O' , '']
                ], 'X', [0,2,'X']
            ],
            "Move comes on Side" => [
                [
                    [ 'O', 'X', 'O'],
                    [ '', 'X', ''],
                    [ 'X' , 'O' , 'X']
                ], 'O', [0,1,'O']
            ],
            "Move comes on Center" => [
                [
                    [ 'O', 'X', 'O'],
                    [ 'X', '', 'O'],
                    [ 'X' , 'O' , 'X']
                ], 'O', [1,1,'O']
            ],
        ];
    }

    /**
     * @dataProvider isGameWonDataProvider
     *
     * @param $boardState
     * @param $playerUnit
     * @param $expected
     */
    public function testIsGameWon($boardState, $playerUnit, $expected)
    {
        $this->assertEquals($expected, $this->move->isGameWon($boardState, $playerUnit));
    }


    public function isGameWonDataProvider()
    {
        return [
            "Won On Top Row" =>[
                [
                    [ 'X', 'X', 'X'],
                    [ 'O', 'O', ''],
                    [ '' , '' , '']
                ], 'X', true],
            "Won On Middle Column" =>[
                [
                    [ 'X', 'O', 'X'],
                    [ 'X', 'O', ''],
                    [ '' , 'O' , '']
                ],'O', true],
            "Won On Diagonal" =>[
                [
                    [ 'X', 'O', 'X'],
                    [ 'O', 'X', ''],
                    [ 'O' , 'O' , 'X']
                ],'X', true],
            "Not Won On Top Row" =>[
                [
                    [ 'X', 'O', 'X'],
                    [ 'O', 'O', ''],
                    [ '' , '' , '']
                ], 'X', false],
            "Not Won On Middle Column" =>[
                [
                    [ 'X', 'O', 'O'],
                    [ 'X', 'O', ''],
                    [ '' , 'X' , '']
                ],'O', false],
            "Not Won On Diagonal" =>[
                [
                    [ 'X', 'X', 'O'],
                    [ 'O', '', 'X'],
                    [ 'O' , 'O' , 'X']
                ],'X', false],

        ];
    }

    /**
     * @dataProvider boardStateInvalidExceptionDataProvider
     *
     * @param $boardState
     * @param $playerUnit
     */
    public function testWrongSetOfElementsThrowsException($boardState, $playerUnit)
    {
        $this->expectException(BoardStateInvalidException::class);
        $this->move->makeMove($boardState, $playerUnit);
    }

    public function boardStateInvalidExceptionDataProvider()
    {
        return[
            "Five O's and Three X" =>[
                [
                    [ 'X', 'O', 'O'],
                    [ 'O', '', 'X'],
                    [ 'O' , 'O' , 'X']
                ],'X'],
            "Two X's and No O" =>[
                [
                    [ 'X', '', ''],
                    [ '', '', 'X'],
                    [ '' , '' , '']
                ],'X'],
            "Three O's and 2 X's with O's Move" => [
                [
                    [ 'X', 'O', ''],
                    [ 'O', '', ''],
                    [ 'X' , '' , 'O']
                ],'O'],
        ];
    }
}
