<?php

$input = file_get_contents('input.txt');
$lines = array_filter(explode(PHP_EOL, $input));

$pairs = [];
for ($i = 0; $i < count($lines); $i++) {
    list($hand, $bid) = explode(' ', $lines[$i]);
    $pairs[] = [
        'hand' => $hand,
        'bid' => $bid,
        'strength' => get_hand_type($hand)
    ];
}

usort($pairs, 'cmp');

$sum = 0;
for ($i = 0; $i < count($pairs); $i++) {
    $h = $pairs[$i]['hand'];
    $b = $pairs[$i]['bid'];
    $r = $i+1;
    echo "hand {$h} has bid {$b} and rank {$r}\n";
    $sum += $pairs[$i]['bid'] * ($i + 1);
}

echo "What are the total winnings? {$sum}\n";

function get_hand_type($hand) {
    $charCounts = array_count_values(str_split($hand));
    sort($charCounts);

    if ($charCounts === [5]) {
        // Five of a kind
        return 7;
    } elseif ($charCounts === [1, 4]) {
        // Four of a kind
        return 6;
    } elseif ($charCounts === [2, 3]) {
        // Full house
        return 5;
    } elseif ($charCounts === [1, 1, 3]) {
        // Three of a kind
        return 4;
    } elseif ($charCounts === [1, 2, 2]) {
        // Two pair
        return 3;
    } elseif ($charCounts === [1, 1, 1, 2]) {
        // One pair
        return 2;
    } elseif ($charCounts === [1, 1, 1, 1, 1]) {
        // High card
        return 1;
    } 
};

function cmp($a, $b) {
    $charStr = [
        'A' => 13,
        'K' => 12,
        'Q' => 11,
        'J' => 10,
        'T' => 9,
        '9' => 8,
        '8' => 7,
        '7' => 6,
        '6' => 5,
        '5' => 4,
        '4' => 3,
        '3' => 2,
        '2' => 1
    ];

    $aStr = $a['strength'];
    $bStr = $b['strength'];

    if ($aStr !== $bStr) {
        return $aStr <=> $bStr;
    }

    $aStr2 = $aStr;
    $bStr2 = $bStr;
    $i = 0;
    while ($aStr2 === $bStr2) {
        $aStr2 = $charStr[str_split($a['hand'])[$i]];
        $bStr2 = $charStr[str_split($b['hand'])[$i]];
        $i++;
    }
    return $aStr2 <=> $bStr2;
}

