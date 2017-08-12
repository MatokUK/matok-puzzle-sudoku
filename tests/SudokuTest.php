<?php

namespace Matok\Puzzle\Test;

use Matok\Puzzle\Sudoku;
use PHPUnit\Framework\TestCase;


class SudokuTest extends TestCase
{
    /**
     * @dataProvider getDumpTests
     */
    public function testDump($sudokuInputArray)
    {
        $sudoku = new Sudoku($sudokuInputArray);

        ob_start();
        $sudoku->dump();
        $sudokuDump = ob_get_clean();
        $exploded = explode(' ',$sudokuDump);
        $items = 0;

        foreach($exploded as $item) {
            if (is_numeric($item)) {
                $items ++;
            }
        }

        $this->assertSame(3*3*9, $items);
    }

    /**
     * @dataProvider getSudokuArrays
     */
    public function testSolve($sudokuInputArray, $expectedResult)
    {
        $sudoku = new Sudoku($sudokuInputArray);
        $computedResult = $sudoku->solve();

        $this->assertSame($expectedResult, $computedResult);
    }

    /**
     * @dataProvider getInvalidConstructorTests
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidConstructorArgumentWillThrowsException($sudokuInvalid)
    {
        $sudoku = new Sudoku($sudokuInvalid);
    }

    public function getDumpTests()
    {
        $input =  array(
            array('6,7,,2,1,9,3,,,9,,2,5,4,3,,7,8,3,4,,8,,,9,1,2,2,3,4,7,6,5,8,9,,,,,4,3,,5,,,,,,1,9,,2,3,4,4,,6,9,,7,,2,3,7,,9,3,2,1,4,5,6,,2,3,6,5,4,7,,9'),
            array('2,,,,,6,3,4,5,5,,3,,1,9,6,7,,,7,6,,4,3,,,2,,,8,7,6,5,2,,4,4,,2,,9,,5,6,,,,5,,,,8,9,1,9,,7,6,5,,1,2,3,3,2,1,,,7,4,5,6,6,,4,3,2,,,,'),
        );

        foreach ($input as $key => $value) {
            $input[$key][0] = explode(',',$value[0]);
        }

        return $input;
    }

    public function getSudokuArrays()
    {
        $input = array(
            array(//'1,,,3,,,2,5,8,2,5,8,1,7,4,3,6,,,6,9,5,8,2,4,7,1,4,7,1,6,9,3,5,8,2,5,8,2,7,4,1,6,,3,6,9,3,8,2,5,7,1,4,7,1,4,9,3,6,8,2,5,8,2,5,4,1,7,9,3,6,9,3,6,2,5,8,1,4,',
                  '1,4,7,3,,,2,5,8,,5,8,,7,,,6,,3,,,,,2,,7,,,,1,,,3,5,,2,,,2,,,,6,9,3,6,,3,8,,,7,,4,,1,4,9,3,6,8,2,,8,,,,,,9,,6,,3,6,,,8,,4,',
                  '1,4,7,3,6,9,2,5,8,2,5,8,1,7,4,3,6,9,3,6,9,5,8,2,4,7,1,4,7,1,6,9,3,5,8,2,5,8,2,7,4,1,6,9,3,6,9,3,8,2,5,7,1,4,7,1,4,9,3,6,8,2,5,8,2,5,4,1,7,9,3,6,9,3,6,2,5,8,1,4,7'),
        );

        foreach ($input as $key => $value) {
            $input[$key][0] = explode(',',$value[0]);
            $input[$key][1] = explode(',',$value[1]);
        }

        foreach($input as $x => $nested) {
            foreach($nested as $y => $sudoku) {
                foreach ($sudoku  as $z => $item) {
                    $input[$x][$y][$z] = (int) $item;
                }
            }
        }

        return $input;
    }

    public function getInvalidConstructorTests()
    {
        return array(
            array(array()),
            array(array(null)),
            array(array(1, 2, 3)),
        );
    }
}