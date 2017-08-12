<?php

namespace Matok\Puzzle;

class Sudoku
{
    private $try = 0;

    private $table;

    private $solution;

    private $resultVector;

    private $solvedRow = array(1, 2, 3, 4, 5, 6, 7, 8, 9);

    public function __construct(array $table)
    {
        $this->checkElementsCount($table);

        foreach ($table as $item) {
            $this->table[] = (int) $item;
        }
        $this->initResultVector($table);

        $this->dump();
       // exit;
    }

    private function checkElementsCount($table)
    {
        $givenCont = count($table);

        if (3*3*9 !== $givenCont) {
            throw new \InvalidArgumentException(sprintf('Sudoku puzzle need to have %d elements - %d elements given!', 3*3*9,  $givenCont));
        }
    }

    private function initResultVector($table)
    {
        foreach ($table as $key => $item) {
            if (empty($item)) {
                $this->resultVector[$key] = $this->getPossibleValuesInRow((int) ($key /9));
            }
        }

        $vector = new SolutionVector($this->resultVector);
        var_dump($vector);
        exit;
    }

    private function getPossibleValuesInRow($rowIdx)
    {
        $rows = array_chunk($this->table, 9);

        return array_diff($this->solvedRow, $rows[$rowIdx]);
    }

    public function solve()
    {
        var_dump($this->resultVector);
        exit;
        do {
            $this->try++;
            $possibleSolution = $this->trySolution();

           // $this->dump($possibleSolution);
            //echo "\n\n\n";
            if ($this->nextPossibilityExists()) {
                $this->nextVector();
            } else {
                break;
            }

         //   if ($this->try % 100000 === 0) {
           //     echo "TRY: {$this->try}\n";
           //     $this->dump($possibleSolution);
         //   echo "\n\n";
             //   var_dump(implode('',$this->resultVector));
            //}

            if ($this->try % 10000099 === 0) {
                $this->dump($possibleSolution);
                var_dump(implode('',$this->resultVector));
                exit;
            }
        } while (!$this->isSolved($possibleSolution));

        echo "\n\n";
        $this->dump($possibleSolution);
        return $possibleSolution;
    }

    private function trySolution()
    {
        $solution = [];
        reset($this->resultVector);

        foreach ($this->table as $item) {
            if (empty($item)) {
                $solution[] = current($this->resultVector);
                next($this->resultVector);
            } else {
                $solution[] = $item;
            }
        }

        return $solution;
    }

    private function isSolved($table)
    {
        $rowsSolved = $this->isRowsSolved($table);
        if (!$rowsSolved) {
            return false;
        }

        $colSolved = $this->isColsSolved($table);

        if (!$colSolved) {
            return false;
        }

        return true;
    }

    private function isRowsSolved($table)
    {
        $rows = array_chunk($table, 9);
        foreach($rows as $row) {
            sort($row);
            if ($row !== $this->solvedRow) {
                return false;
            }
        }

        return true;
    }

    private function isColsSolved($table)
    {
        $cols = array();
        $rows = array_chunk($table, 9);
        //$this->dump($table);
        for ($idx = 0; $idx < 9; $idx++) {
            foreach ($rows as $row) {
                $cols[$idx][] = $row[$idx];
            }
        }

        foreach($cols as $col) {
            sort($col);
            if ($col !== $this->solvedRow) {
                return false;
            }
        }
        echo 'col is solved';

        return true;
    }

    private function nextPossibilityExists()
    {
        $vector = implode('', $this->resultVector);

        return !preg_match('/^9+$/', $vector);
    }

    private function nextVector()
    {
        $vector = 'X'.implode('', $this->resultVector);
        $vector++;
        $vector = str_split($vector);
        unset($vector[0]);

        $this->resultVector = [];

        foreach($vector as $item)
            $this->resultVector[] = (int) $item;
    }

    public function dump($table = null)
    {
        if (empty($table)) {
            $table = $this->table;
        }
        $idx = 1;
        foreach ($table as $item) {
            printf(" %s ", empty($item) ? '*' : $item);
            if ($idx % 9 == 0) {
                printf("\n");
            } elseif ($idx %3 == 0) {
                printf("|");
            }

            if (27 === $idx || 54 === $idx) {
                printf("----------------------------\n");
            }
            $idx ++;
        }
    }
}