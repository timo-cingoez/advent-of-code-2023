<?php

$input = file_get_contents('input.txt');
$lines = explode(PHP_EOL, $input);

preg_match_all('/([0-9])+/', $lines[0], $matches);
$time = implode('', $matches[0]);

preg_match_all('/([0-9])+/', $lines[1], $matches);
$distance = implode('', $matches[0]);

echo "Time: {$time} Distance: {$distance}\n";

$winNums = [];
$x = 1;
while ($x < $time) {
    if ($x * ($time - $x) > $distance) {
        $winNums[] = $x;
    }
    $x++;
}

$sum = count($winNums);
echo "How many ways can you beat the record in this one much longer race? {$sum}\n";

