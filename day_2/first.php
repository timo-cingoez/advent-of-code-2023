<?php

$input = file_get_contents('input.txt');
$games = array_filter(explode(PHP_EOL, $input));

$maxColors = ['red' => 12, 'green' => 13, 'blue' => 14];

$sumOfPossibleGameIds = 0;
foreach ($games as $game) {
    $isPossible = true;

    $setList = explode('; ', explode(': ', $game)[1]);
    for ($i = 0; $i < count($setList); $i++) {
        $set = explode(', ', $setList[$i]);
        for ($j = 0; $j < count($set); $j++) {
            list($count, $color) = explode(' ', $set[$j]);
            if ((int)$count > $maxColors[$color]) {
                $isPossible = false;
                break 2;
            }
        }
    }

    if ($isPossible) {
        $id = str_replace(':', '', explode(' ', $game)[1]);
        $sumOfPossibleGameIds += (int)$id; 
    }
}

echo "Sum of the possible game IDs: {$sumOfPossibleGameIds}\n";

