<?php

$input = file_get_contents('input.txt');
$lines = array_filter(explode(PHP_EOL, $input));

$pairs = [];
for ($i = 0; $i < count($lines); $i++) {
    list($hand, $bid) = explode(' ', $lines[$i]);
    #echo "{$hand} {$bid}\n";
    $pairs[] = ['hand' => $hand, 'bid' => $bid, 'strength' => 0];
}

for ($i = 0; $i < count($pairs); $i++) {
    $pairs[$i]['strength'] = get_hand_strength($pairs[$i]['hand']);
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

function get_hand_strength($hand) {
    $strength = 0;

    $uniqueChars = array_unique(str_split($hand));
    $uniqueCharCount = count($uniqueChars);

    // Five of a kind
    if ($uniqueCharCount === 1) {
        $strength = 7;
    }

    // Four of a kind / Full house
    if ($uniqueCharCount === 2) {
        $firstCharCount = count(array_keys($uniqueChars, $uniqueChars[0]));
        if ($firstCharCount === 1 || $firstCharCount === 4) {
            $strength = 6;
        }
        if ($firstCharCount === 3 || $firstCharCount === 2) {
            $strength = 5;
        }
    }

    // Three of a kind / Two pair
    if ($uniqueCharCount === 3) {
        foreach ($uniqueChars as $char) {
            if (count(array_keys(str_split($hand), $char)) === 2) {
                $strength = 3;
                break;
            }
            if (count(array_keys(str_split($hand), $char)) === 3) {
                $strength = 4;
                break;
            }
        }
    }

    // One pair
    if ($uniqueCharCount === 2 || $uniqueCharCount === 4) {
        $strength = 2;
    }

    // High card
    if ($uniqueCharCount === 5) {
        $strength = 1;
    }

    return $strength;
}

function cmp($a, $b) {
    #echo "Comparing {$a['hand']} ({$a['strength']}) with {$b['hand']} ({$b['strength']})\n";

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

