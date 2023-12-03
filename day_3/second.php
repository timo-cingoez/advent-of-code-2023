<?php

$input = file_get_contents('input.txt');
$lines = array_filter(explode(PHP_EOL, $input));

$numberPosInLines = [];
for ($i = 0; $i < count($lines); $i++) {
    preg_match_all('/([0-9])+/', $lines[$i], $matches);
    $nums = $matches[0];
    
    $offset = 0;
    for ($j = 0; $j < count($nums); $j++) {
        $startPos = strpos($lines[$i], $nums[$j], $offset);
        $endPos = $startPos + (strlen($nums[$j]) - 1);
        $offset = $endPos + 1;

        $numberPosInLines[$i][$startPos] = $nums[$j];
        $numberPosInLines[$i][$endPos] = $nums[$j];
        if (strlen($nums[$j]) > 2) {
            $numberPosInLines[$i][$startPos + 1] = $nums[$j];
        }
    }
}

$sum = 0;
for ($i = 0; $i < count($lines); $i++) {
    preg_match_all('/[*]/', $lines[$i], $matches);
    $stars = $matches[0];

    $offset = 0;
    for ($j = 0; $j < count($stars); $j++) {
        $adjNums[$j] = [];
        $startPos = strpos($lines[$i], $stars[$j], $offset);
        $endPos = $startPos + (strlen($stars[$j]) - 1);
        $offset = $endPos + 1;

        $adjPosList = [
            $left = isset($lines[$i][$startPos - 1]) ? ($startPos - 1) : 0,
            $startPos,
            $right = isset($lines[$i][$endPos + 1]) ? ($endPos + 1) : strlen($lines[$i]) - 1,
            $endPos
        ];

        // Check current line.
        if (is_numeric($lines[$i][$left])) {
            $adjNums[$j][] = $numberPosInLines[$i][$left];
        }
        if (is_numeric($lines[$i][$right])) {
            $adjNums[$j][] = $numberPosInLines[$i][$right];
        }

        // Check line on $i - 1.
        if (isset($lines[$i - 1])) {
            for ($k = 0; $k < count($adjPosList); $k++) {
                if (is_numeric($lines[$i - 1][$adjPosList[$k]])) {
                    if (!in_array($numberPosInLines[$i - 1][$adjPosList[$k]], $adjNums[$j])) {
                        $adjNums[$j][] = $numberPosInLines[$i - 1][$adjPosList[$k]];
                    }
                }
            }
        }

        // Check line on $i + 1.
        if (isset($lines[$i + 1])) {
            for ($k = 0; $k < count($adjPosList); $k++) {
                if (is_numeric($lines[$i + 1][$adjPosList[$k]])) {
                    if (!in_array($numberPosInLines[$i + 1][$adjPosList[$k]], $adjNums[$j])) {
                        $adjNums[$j][] = $numberPosInLines[$i + 1][$adjPosList[$k]];
                    }
                }
            }
        }

        if (count($adjNums[$j]) === 2) {
            $sum += $adjNums[$j][0] * $adjNums[$j][1];
        }
    }

}

echo "Sum of num products of stars with 2 adjactent nums: {$sum}\n";

