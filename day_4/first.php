<?php

$input = file_get_contents('input.txt');
$lines = array_filter(explode(PHP_EOL, $input));

$sum = 0;
for ($i = 0; $i < count($lines); $i++) {
    $_ = explode(' | ', explode(': ', $lines[$i])[1]);
    $winningNums = array_values(array_filter(explode(' ', $_[0])));
    $nums = array_values(array_filter(explode(' ', $_[1])));

    $cardSum = 0;
    for ($j = 0; $j < count($nums); $j++) {
        if (in_array($nums[$j], $winningNums)) {
            $cardSum = $cardSum === 0 ? 1 : $cardSum * 2;
        }
    }

    $sum += $cardSum;
}

echo "How many points are they worth in total? {$sum}\n";

