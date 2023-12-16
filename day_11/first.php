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
    print_r($verticalLine);
    if (count(array_count_values($verticalLine)) === 1) {
        $verticals[] = $i;
    }
}

$horiGrid = [];
for ($i = 0; $i < count($lines); $i++) {
    $horiGrid[] = $lines[$i];
    if (in_array($i, $horizontals)) {
        $horiGrid[] = $lines[$i];
    }
}

$vertiGrid = [];
for ($i = 0; $i < count($horiGrid); $i++) {
    $newRow = [];
    for ($j = 0; $j < strlen($horiGrid[$i]); $j++) {
        $newRow[] = $horiGrid[$i][$j];
        if (in_array($j, $verticals)) {
            $newRow[] = $horiGrid[$i][$j];
        }
    }
    $vertiGrid[] = implode('', $newRow);
}

$galC = 0;
foreach ($vertiGrid as $r) {
    $galC += substr_count($r, '#');
}
$pairCount = ($galC - 1) * (($galC - 1) / 2) + (($galC - 1) / 2);
echo $pairCount."\n";

foreach ($vertiGrid as $r) {
    echo "{$r}\n";
}

echo PHP_EOL.PHP_EOL;

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

foreach ($vertiGrid as $r) {
    echo "{$r}\n";
}

$stepAll = [];
foreach ($galPos as $gal1 => $pos1) {
    $stepAll[$gal1] = [];
    foreach ($galPos as $gal2 => $pos2) {
        if ($gal1 < $gal2) {
            $steps = 0;
            $currPos = $pos1;
            // Vertical
            while ($currPos[0] < $pos2[0]) {
                $currPos[0]++;
                $steps++;
            }

            // Horizontal
            while ($currPos !== $pos2) {
                // walk right
                if ($currPos[1] < $pos2[1]) {
                    $currPos[1]++;
                    $steps++;
                }

                if ($currPos[1] > $pos2[1]) {
                    $currPos[1]--;
                    $steps++;
                }
            }

            $stepAll[$gal1][] = [
                'from' => $gal1,
                'to' => $gal2,
                'steps' => $steps
            ];
        }
    }
}

print_r($stepAll);
$totalSteps = 0;
foreach ($stepAll as $stepp) {
    foreach ($stepp as $steppo) {
        $totalSteps += $steppo['steps'];
    }
    
}
echo "total steps: {$totalSteps}\n";
