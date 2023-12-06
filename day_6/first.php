<?php

$input = file_get_contents('input.txt');
$lines = explode(PHP_EOL, $input);

preg_match_all('/([0-9])+/', $lines[0], $matches);
$times = $matches[0];

preg_match_all('/([0-9])+/', $lines[1], $matches);
$distances = $matches[0];

$data = array_combine($times, $distances);

$winNums = [];
foreach ($data as $time => $distance) {
    echo "Time: {$time} Distance: {$distance}\n";
    $x = 1;
    while ($x < $time) {
        if ($x * ($time - $x) > $distance) {
            $winNums[$time][] = $x;
        }
        $x++;
    }
}

$sum = count($winNums['41']);
unset($winNums['41']);
foreach ($winNums as $nums) {
    $sum *= count($nums);
}
echo "What do you get if you multiply these numbers together? {$sum}\n";
