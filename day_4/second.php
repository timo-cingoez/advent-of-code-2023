<?php

$input = file_get_contents('input.txt');
$lines = array_filter(explode(PHP_EOL, $input));

$counts = array_fill(0, count($lines), 0);
for ($i = 0; $i < count($lines); $i++) {
    $counts[$i] += 1;

    $_ = explode(' | ', explode(': ', $lines[$i])[1]);
    $winNums = array_values(array_filter(explode(' ', $_[0])));
    $nums = array_values(array_filter(explode(' ', $_[1])));

    $winNumCount = 0;
    for ($j = 0; $j < count($nums); $j++) {
        if (in_array($nums[$j], $winNums)) {
            $winNumCount++;
        }
    }

    for ($j = 0; $j < $winNumCount; $j++) {
        $counts[$i + 1 + $j] += $counts[$i];
    }
}

$sum = 0;
for ($i = 0; $i < count($counts); $i++) {
    $sum += $counts[$i];
}
echo $sum."\n";

