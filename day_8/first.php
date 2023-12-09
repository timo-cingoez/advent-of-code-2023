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

$stepCount = 0;
$currentEl = 'AAA';
for ($i = 0; $i < count($instructions); $i++) {
    $step = $instructions[$i];
    $nextEl = $step === 'L' ? reset($nodes[$currentEl]) : end($nodes[$currentEl]);

    echo "Stepped from {$currentEl} to {$nextEl}\n";

    $currentEl = $nextEl;
    $stepCount++;

    if ($currentEl !== 'ZZZ' && !isset($instructions[$i + 1])) {
        $i = -1;
        echo "Reached end of instructions, resetting\n";
    } elseif ($currentEl === 'ZZZ') {
        break;
    }
}

echo "How many steps are required to reach ZZZ? {$stepCount}\n";

