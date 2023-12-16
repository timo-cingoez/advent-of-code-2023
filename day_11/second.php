<?php

$input = file_get_contents('input.txt');
$lines = array_filter(explode(PHP_EOL, $input));

// Find horizontals
$horizontals = [];
foreach ($lines as $i => $line) {
    if (strpos($line, '#') === false) {
        $horizontals[] = $i;
    }
}

// Find verticals
$verticals = [];
for ($i = 0; $i < strlen($lines[0]); $i++) {
    $verticalLine = [];
    for ($j = 0; $j < count($lines); $j++) {
        $verticalLine[] = $lines[$j][$i];
    }
    if (count(array_count_values($verticalLine)) === 1) {
        $verticals[] = $i;
    }
}

$vertiGrid = $lines;

$n = 1;
$galPos = [];
foreach ($vertiGrid as $i => $r) {
    $k = false;
    $p = str_split($r);

    $k = array_search('#', $p);
    while($k !== false) {
        $galPos[$n] = [$i, $k];
        $p[$k] = $n++; 
        $k = array_search('#', $p);
    }
    $vertiGrid[$i] = implode('', $p);
}

echo "starting pos calc...\n";
$totStep = 0;
$stepAll = [];
foreach ($galPos as $gal1 => $pos1) {
    $stepAll[$gal1] = [];
    foreach ($galPos as $gal2 => $pos2) {
        if ($gal1 < $gal2) {
            $vertiPassed = 0;
            $horiPassed = 0;
            $steps = 0;
            $currPos = $pos1;

            // Vertical
            while ($currPos[0] < $pos2[0]) {
                $currPos[0]++;
                if (in_array($currPos[0], $horizontals)) {
                    $horiPassed++;
                }
                $steps++;
            }

            // Horizontal
            while ($currPos !== $pos2) {
                // walk right
                if ($currPos[1] < $pos2[1]) {
                    $currPos[1]++;
                    if (in_array($currPos[1], $verticals)) {
                        $vertiPassed++;
                    }
                    $steps++;
                }

                if ($currPos[1] > $pos2[1]) {
                    if (in_array($currPos[1], $verticals)) {
                        $vertiPassed++;
                    }
                    $currPos[1]--;
                    $steps++;
                }
            }

            $totStep += $steps + ($vertiPassed + $horiPassed) * 999999;
        }
    }
}

echo "total steps: {$totStep}\n";

