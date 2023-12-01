<?php

$input = file_get_contents('input.txt');
$lines = array_filter(explode(PHP_EOL, $input));
$numberString = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];

$numbersInLines = [];
for ($i = 0; $i < count($lines); $i++) {
    for ($j = 0; $j < strlen($lines[$i]); $j++) {
        if (is_numeric($lines[$i][$j])) {
            $numbersInLines[$i][] = $lines[$i][$j];
        }
    }
}
// print_r($numbersInLines);

$calibrationValues = [];
for ($i = 0; $i < count($numbersInLines); $i++) {
    if (count($numbersInLines[$i]) > 1) {
        $firstNum = $numbersInLines[$i][0];
        $lastNum = $numbersInLines[$i][array_key_last($numbersInLines[$i])];
        $calibrationValues[] = $firstNum.''.$lastNum;
    } else {
        $calibrationValues[] = $numbersInLines[$i][0].''.$numbersInLines[$i][0];
    }
}
// print_r($calibrationValues);

$calibrationSum = 0;
for ($i = 0; $i < count($calibrationValues); $i++) {
    $calibrationSum += $calibrationValues[$i];
}

echo "Calibration sum: {$calibrationSum} \n";

