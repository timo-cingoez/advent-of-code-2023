<?php

$input = file_get_contents('input.txt');
$lines = array_filter(explode(PHP_EOL, $input));

$histories = [];
foreach ($lines as $line) {
    $histories[] = array_map('intval', explode(' ', $line));
}

$diffSeqs = [];
$sum = 0;
for ($i = 0; $i < count($histories); $i++) {
    $diffSeqs[$i][] = $histories[$i];
    $diffSeq = get_diff_seq($histories[$i]);
    $diffSeqs[$i][] = $diffSeq;

    while (!is_zero_seq($diffSeq)) {
        $diffSeq = get_diff_seq($diffSeq);
        $diffSeqs[$i][] = $diffSeq;
    }

    $diffSeqs[$i] = array_reverse($diffSeqs[$i]);
    // handle the 0 seq
    $diffSeqs[$i][0][] = 0;
    for ($j = 1; $j < count($diffSeqs[$i]); $j++) {
        $next = end($diffSeqs[$i][$j]) + end($diffSeqs[$i][$j - 1]);
        $diffSeqs[$i][$j][] = $next;
    }

    $lastSeq = end($diffSeqs[$i]);
    $lastNum = end($lastSeq);
    echo "last num in history {$lastNum}\n";
    $sum += $lastNum;
}

echo "What is the sum of these extrapolated values? {$sum}\n";

function get_diff_seq($seq) {
    $diffSeq = [];
    for ($i = 0; $i < count($seq) - 1; $i++) {
        $diffSeq[] = $seq[$i + 1] - $seq[$i];
    }
    return $diffSeq;
}

function is_zero_seq($seq) {
    foreach ($seq as $num) {
        if ($num !== 0) {
            return false;
        }
    }
    return true;
}

