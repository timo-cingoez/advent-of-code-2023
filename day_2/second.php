<?php

$input = file_get_contents('input.txt');
$games = array_filter(explode(PHP_EOL, $input));

$powerSum = 0;
foreach ($games as $game) {
    $isPossible = true;

    $tmp = explode(' ', $game);
    $id = str_replace(':', '', $tmp[1]);

    $tmp = explode(': ', $game);
    $setList = explode('; ', $tmp[1]);

    $colorMin = [
        'red' => 0,
        'green' => 0,
        'blue' => 0
    ];
    for ($i = 0; $i < count($setList); $i++) {
        $set = explode(', ', $setList[$i]);
        for ($j = 0; $j < count($set); $j++) {
            $cubeCountAndColor = explode(' ', $set[$j]);
            if ((int)$cubeCountAndColor[0] > $colorMin[$cubeCountAndColor[1]]) {
                $colorMin[$cubeCountAndColor[1]] = $cubeCountAndColor[0];
            }
        }
    }

    $colorMins[$id] = $colorMin;
    $powerSum += $colorMin['red'] * $colorMin['green'] * $colorMin['blue'];
}

print_r($colorMins);

echo "Sum of the powers of the minimum sets of cubes: {$powerSum}\n";

