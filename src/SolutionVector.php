<?php

namespace Matok\Puzzle;


class SolutionVector
{
    private $vector;

    public function __construct($possibilities) {
        foreach ($possibilities as $possibility) {
            $arrayIterator = new \ArrayIterator($possibility);

            $this->vector[] = $arrayIterator;
        }


    }

}