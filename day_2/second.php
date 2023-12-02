<?php

$input = file_get_contents('input.txt');
$games = array_filter(explode(PHP_EOL, $input));

$sumOfPowers = 0;
foreach ($games as $game) {
    $minColors = ['red' => 0, 'green' => 0, 'blue' => 0];

    $setList = explode('; ', explode(': ', $game)[1]);
    for ($i = 0; $i < count($setList); $i++) {
        $set = explode(', ', $setList[$i]);
        for ($j = 0; $j < count($set); $j++) {
            list($count, $color) = explode(' ', $set[$j]);
            if ((int)$count > $minColors[$color]) {
                $minColors[$color] = (int)$count;
            }
        }
    }

    $sumOfPowers += $minColors['red'] * $minColors['green'] * $minColors['blue'];
}

echo "Sum of the powers of the minimum sets of cubes: {$sumOfPowers}\n";

