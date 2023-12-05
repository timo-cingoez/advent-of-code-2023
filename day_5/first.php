<?php
$input = file_get_contents('input.txt');
$lines = explode(PHP_EOL.PHP_EOL, $input);

$seeds = explode(' ', explode(': ', $lines[0])[1]);
# print_r($seeds);

$data = [];
for ($i = 1; $i < count($lines); $i++) {
    $tmp = explode(':', $lines[$i]);
    $key = str_replace(' map', '', $tmp[0]);
    $nums = array_values(array_filter(explode(PHP_EOL, $tmp[1])));
    for ($j = 0; $j < count($nums); $j++) {
        $data[$key][] = explode(' ', $nums[$j]);
    }
}

$locations = [];
for ($i = 0; $i < count($seeds); $i++) {
    $protagonist = null;
    foreach ($data as $key => $val) {
        $inRange = false;
        for ($j = 0; $j < count($data[$key]); $j++) {
            $destination = $data[$key][$j][0];
            $source = $data[$key][$j][1];
            $range = $data[$key][$j][2];

            $protagonist = is_null($protagonist) ? $seeds[$i] : $protagonist;
            if ($protagonist >= $source && $protagonist < $source + ($range - 1)) {
                $protagonist = $destination + ($protagonist - $source);
                $inRange = true;
                break;
            }
        }
        if (!$inRange) {
            $protagonist = $protagonist;
        }
        
        if ($key === 'humidity-to-location') {
            $locations[$seeds[$i]] = $protagonist;
        }

        echo "seed {$seeds[$i]} has destination {$protagonist} in context {$key}\n";
    }
}

print_r($locations);
$min = min($locations);
echo "What is the lowest location number that corresponds to any of the initial seed numbers? {$min}\n";

// destination    source         length
// 50[50...51]    98[98...99]    2
// 52[52...99]    50[50...97]    48
