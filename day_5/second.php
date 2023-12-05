<?php
$input = file_get_contents('input2.txt');
$lines = explode(PHP_EOL.PHP_EOL, $input);

$seedRanges = array_chunk(explode(' ', explode(': ', $lines[0])[1]), 2);

$data = [];
for ($i = 1; $i < count($lines); $i++) {
    $tmp = explode(':', $lines[$i]);
    $key = str_replace(' map', '', $tmp[0]);
    $nums = array_values(array_filter(explode(PHP_EOL, $tmp[1])));
    for ($j = 0; $j < count($nums); $j++) {
        $data[$key][] = explode(' ', $nums[$j]);
    }
}

$dataLen = [];
foreach ($data as $key => $val) {
    $dataLen[$key] = count($val);
}


$minLocation = PHP_INT_MAX;
$currentSeed = $seedRanges[0][0];
$seedRangeLen = count($seedRanges);
$currentRangeIdx = 0;
while ($currentSeed) {
    $protagonist = null;
    foreach ($data as $key => $val) {
        $inRange = false;
        for ($j = 0; $j < $dataLen[$key]; $j++) {
            $destination = $data[$key][$j][0];
            $source = $data[$key][$j][1];
            $range = $data[$key][$j][2];

            $protagonist = is_null($protagonist) ? $currentSeed : $protagonist;
            if ($protagonist >= $source && $protagonist < $source + ($range - 1)) {
                $protagonist = $destination + ($protagonist - $source);
                $inRange = true;
                break;
            }
        }
        if (!$inRange) {
            $protagonist = $protagonist;
        }
        
        if ($key === 'humidity-to-location' && $protagonist < $minLocation) {
            $minLocation = $protagonist;
        }
    }

    if ($currentSeed < $seedRanges[$currentRangeIdx][0] + $seedRanges[$currentRangeIdx][1] - 1) {
        $currentSeed++;
    } else {
        if ($seedRangeLen - 1 === $currentRangeIdx) {
           break;
        }
        $currentSeed = $seedRanges[++$currentRangeIdx][0];
    }
}

echo "What is the lowest location number that corresponds to any of the initial seed numbers? {$minLocation}\n";

// destination    source         length
// 50[50...51]    98[98...99]    2
// 52[52...99]    50[50...97]    48
