<?php

// 12 red, 13 green, 14 blue

$input = file_get_contents('input.txt');
$games = array_filter(explode(PHP_EOL, $input));

$maxCubeColors = ['red' => 12, 'green' => 13, 'blue' => 14];

$sumOfPossibleGameIds = 0;
foreach ($games as $game) {
    $isPossible = true;

    $tmp = explode(' ', $game);
    $id = str_replace(':', '', $tmp[1]);

    $tmp = explode(': ', $game);
    $setList = explode('; ', $tmp[1]);


    for ($i = 0; $i < count($setList); $i++) {
        $set = explode(', ', $setList[$i]);
        for ($j = 0; $j < count($set); $j++) {
            $cubeCountAndColor = explode(' ', $set[$j]);
            if ((int)$cubeCountAndColor[0] > $maxCubeColors[$cubeCountAndColor[1]]) {
                $isPossible = false;
                break 2;
            }
        }
    }


    if ($isPossible) {
        $sumOfPossibleGameIds += $id;
    }
}

//print_r($sets);

echo "Sum of the possible game IDs: {$sumOfPossibleGameIds}\n";
