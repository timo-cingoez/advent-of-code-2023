<?php

$input = file_get_contents('input.txt');
$lines = array_filter(explode(PHP_EOL, $input));

$instructions = str_split($lines[0]);
unset($lines[0]);

$nodes = [];
foreach ($lines as $line) {
    list($name, $elementsStr) = explode(' = ', $line);
    $elements = explode(' ', str_replace(['(', ',', ')'], '', $elementsStr));
    $nodes[$name] = $elements;
}

$currElList = get_start_nodes($nodes);
$stepCount = 0;
$foundZ = [];
$res = 0;
for ($i = 0; $i < count($instructions); $i++) {
    $step = $instructions[$i];

    $stepCount++;
    foreach ($currElList as $startEl => $currEl) {
        $lastEl = $currEl;
        $currElList[$startEl] = $step === 'L' ? reset($nodes[$currEl]) : end($nodes[$currEl]);
        echo "Start node {$startEl} stepped from {$lastEl} to {$currElList[$startEl]}\n";

        if (substr($currElList[$startEl], -1) === 'Z') {
            $foundZ[$startEl] = $stepCount;
        }
        if (count($foundZ) === count($currElList)) {
            print_r($foundZ);
            $res = array_lcm($foundZ);
            break;
        }
    }

    if ($res) break;

    if (!isset($instructions[$i + 1])) {
        $i = -1;
    }
}

echo "How many steps are required to reach ZZZ? {$res}\n";

function get_start_nodes($nodes) {
    $startNodes = [];
    foreach (array_keys($nodes) as $name) {
        if (substr($name, -1) === 'A') {
            $startNodes[$name] = $name;
        }
    }
    return $startNodes;
}

function gcd($a, $b)
{
    while ($b != 0) {
        $temp = $b;
        $b = $a % $b;
        $a = $temp;
    }
    return $a;
}

function lcm($a, $b)
{
    return ($a / gcd($a, $b)) * $b;
}

function array_lcm($numbers)
{
    $lcm = 1;
    
    foreach ($numbers as $number) {
        $lcm = lcm($lcm, $number);
    }
    
    return $lcm;
}
