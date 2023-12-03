<?php

$input = file_get_contents('input.txt');
$lines = array_filter(explode(PHP_EOL, $input));

$symbols = ['*', '-', '#', '/', '=', '%', '$', '&', '@', '+'];
$sumPartNums = 0;
for ($i = 0; $i < count($lines); $i++) {
    preg_match_all('/([0-9])+/', $lines[$i], $matches);
    $nums = $matches[0];

    $offset = 0;
    for ($j = 0; $j < count($nums); $j++) {
        $found = false;
        $startPos = strpos($lines[$i], $nums[$j], $offset);
        $endPos = $startPos + (strlen($nums[$j]) - 1);
        $offset = $endPos + 1;

        $adjPosList = [
            $left = isset($lines[$i][$startPos - 1]) ? ($startPos - 1) : 0,
            $startPos,
            $right = isset($lines[$i][$endPos + 1]) ? ($endPos + 1) : strlen($lines[$i]) - 1,
            $endPos
        ];
        if (strlen($nums[$j]) > 2) {
            $adjPosList[] = $startPos + 1;
        }

        // Check current line.
        $before = in_array($lines[$i][$left], $symbols); 
        $after = in_array($lines[$i][$right], $symbols); 
        if ($before || $after) {
            $numsWithAdjSymbols[$i][] = $nums[$j];
            $found = true;
        }

        // Check line on $i - 1.
        if (!$found && isset($lines[$i - 1])) {
            for ($k = 0; $k < count($adjPosList); $k++) {
                if (in_array($lines[$i - 1][$adjPosList[$k]], $symbols)) {
                    $numsWithAdjSymbols[$i][] = $nums[$j];
                    $found = true;
                    break;
                }
            }
        }

        // Check line on $i + 1.
        if (!$found && isset($lines[$i + 1])) {
            for ($k = 0; $k < count($adjPosList); $k++) {
                if (in_array($lines[$i + 1][$adjPosList[$k]], $symbols)) {
                    $numsWithAdjSymbols[$i][] = $nums[$j];
                    $found = true;
                    break;
                }
            }
        }
    }

}

$sum = 0;
for ($i = 0; $i < count($numsWithAdjSymbols); $i++) {
    for ($j = 0; $j < count($numsWithAdjSymbols[$i]); $j++) {
        $sum += $numsWithAdjSymbols[$i][$j];
    }
}

echo "Sum of nums with adjactent symbols: {$sum}\n";

