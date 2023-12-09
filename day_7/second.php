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
    $sum += $pairs[$i]['bid'] * ($i + 1);
}

echo "What are the total winnings? {$sum}\n";

function get_hand_type($hand) {
    if (strpos($hand, 'J') !== false) {
        $cleanedChars = array_filter(str_split(str_replace('J', '', $hand)));
        if ($cleanedChars === []) {
            $hand = 'AAAAA';
        } else {
            $valCount = array_count_values($cleanedChars);
            asort($valCount);
            end($valCount);
            $maxChar = key($valCount);
            $hand = str_replace('J', $maxChar, $hand);
        }
    }

    $charCounts = array_count_values(str_split($hand));
    sort($charCounts);

    assert(count($charCounts) === 5 && strpos('J', $hand) === false);

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

    assert(false);
}

function cmp($a, $b) {
    $charStr = [
        'A' => 13,
        'K' => 12,
        'Q' => 11,
        'T' => 10,
        '9' => 9,
        '8' => 8,
        '7' => 7,
        '6' => 6,
        '5' => 5,
        '4' => 4,
        '3' => 3,
        '2' => 2,
        'J' => 1
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
        assert($i < 5);
        $aStr2 = $charStr[str_split($a['hand'])[$i]];
        $bStr2 = $charStr[str_split($b['hand'])[$i]];
        $i++;
    }
    return $aStr2 <=> $bStr2;
}

