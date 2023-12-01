<?php

$input = file_get_contents('input.txt');
$lines = array_filter(explode(PHP_EOL, $input));
$stringNumbers = array(
    "twone" => "21",
    "sevenine" => "79",
    "oneight" => "18",
    "threeight" => "38",
    "nineight" => "98",
    "fiveight" => "58",
    "eighthree" => "83",
    "eightwo" => "82",
    "one" => "1",
    "two" => "2",
    "three" => "3",
    "four" => "4",
    "five" => "5",
    "six" => "6",
    "seven" => "7",
    "eight" => "8",
    "nine" => "9"
);

$numbersInLines = [];
for ($i = 0; $i < count($lines); $i++) {
    $line = $lines[$i];
    foreach ($stringNumbers as $string => $num) {
        $line = str_replace($string, $num, $line);
    }
    for ($j = 0; $j < strlen($line); $j++) {
        if (is_numeric($line[$j])) {
            $numbersInLines[$i][] = $line[$j];
        }
    }
}
// print_r($numbersInLines);

$calibrationValues = [];
for ($i = 0; $i < count($numbersInLines); $i++) {
    if (count($numbersInLines[$i]) > 1) {
        $firstNum = $numbersInLines[$i][0];
        $lastNum = $numbersInLines[$i][array_key_last($numbersInLines[$i])];
        $calibrationValues[] = intval($firstNum.''.$lastNum);
    } else {
        $num = $numbersInLines[$i][0];
        $calibrationValues[] = intval($num.''.$num);
    }
}
// print_r($calibrationValues);

$calibrationSum = 0;
for ($i = 0; $i < count($calibrationValues); $i++) {
    $calibrationSum += $calibrationValues[$i];
}

echo "Calibration sum: {$calibrationSum} \n";


