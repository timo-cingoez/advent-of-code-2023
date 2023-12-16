<?php

$input = file_get_contents('sample.txt');
$lines = array_filter(explode(PHP_EOL, $input));

$validStepTiles = [
    '|' => [
        'UP' => ['|', '7', 'F', 'S'],
        'DOWN' => ['L', 'J', '|', 'S'],
        'LEFT' => [],
        'RIGHT' => []
    ],
    '-' => [
        'UP' => [],
        'DOWN' => [],
        'LEFT' => ['-', 'S', 'L', 'F'],
        'RIGHT' => ['-', 'S', '7', 'J']
    ],
    'L' => [
        'UP' => ['|', 'S', 'F', '7'],
        'DOWN' => [],
        'LEFT' => [],
        'RIGHT' => ['-', 'S', 'J', '7']
    ],
    'J' => [
        'UP' => ['|', 'S', 'F', '7'],
        'DOWN' => [],
        'LEFT' => ['-', 'S', 'L', 'F'],
        'RIGHT' => []
    ],
    '7' => [
        'UP' => [],
        'DOWN' => ['|', 'S', 'L', 'J'],
        'LEFT' => ['L', '-', 'F', 'S'],
        'RIGHT' => []
    ],
    'F' => [
        'UP' => [],
        'DOWN' => ['|', 'S', 'L', 'J'],
        'LEFT' => [],
        'RIGHT' => ['-', 'S', 'J', '7']
    ],
    'S' => [
        'UP' => ['|', '-', 'L', 'J', '7', 'F'],
        'DOWN' => ['|', '-', 'L', 'J', '7', 'F'],
        'LEFT' => ['|', '-', 'L', 'J', '7', 'F'],
        'RIGHT' => ['|', '-', 'L', 'J', '7', 'F']
    ]
];

$validDirections = [
    '|' => ['UP', 'DOWN'],
    '-' => ['LEFT', 'RIGHT'],
    'L' => ['UP', 'RIGHT'],
    'J' => ['UP', 'LEFT'],
    '7' => ['DOWN', 'LEFT'],
    'F' => ['DOWN', 'RIGHT'],
    'S' => ['UP', 'DOWN', 'RIGHT', 'LEFT']
];

$tileCount = 0;
$startStep = null;
$grid = [];
foreach ($lines as $rowIdx => $row) {
    $tiles = str_split($row);
    foreach ($tiles as $colIdx => $tile) {
        $tileCount++;
        if ($tile === 'S') {
            $startStep = [$rowIdx, $colIdx];
        }
    }
    $grid[] = $tiles;
}
$outGrid = $grid;

$visited = [];
$currStep = $startStep;
$stepCount = 1;
while (true) {
    $visited[] = $currStep;

    $outGrid[$currStep[0]][$currStep[1]] = 'X';
    $currTile = get_tile($currStep);
    $directions = get_valid_step_directions($currStep);

    if (count($directions) === 0) {
        echo "stopping, no valid directions left at pos [{$currStep[0]}, {$currStep[1]}]\n";
        break;
    }

    $tiles = get_valid_tiles_for_directions($currTile, $directions);
    
    $nextStep = get_next_step($currStep, $directions, $tiles);
    $currStep = $nextStep;

    $stepCount++;
}

// Convert rows to strings
for ($i = 0; $i < count($outGrid); $i++) {
    $rowString = implode('', $outGrid[$i]);
    $outGrid[$i] = str_replace(['|', '-', 'L', 'J', '7', 'F', 'S'], '.', $rowString);
}

// Scale up grid by factor 3
$scaledGrid = scale_up_grid($outGrid, 3);
add_border_to_grid($scaledGrid, '.');

flood_fill_grid($scaledGrid, 1, 1, ".", " ");
#print_grid($scaledGrid);

$dotCount = 0;
foreach ($scaledGrid as $row) {
    $dotCount += substr_count($row, '.');
}
echo (string)($dotCount / 3)."\n";
exit;
$row2Grid = halveResolution($x2Grid);

echo "Original resolution grid after flooding\n";

$dotCount = 0;
foreach ($row2Grid as $row) {
    foreach ($row as $tile) {
        if ($tile === '....') {
            $dotCount++;
        }
    }
}
echo "Dots surrounded by loop: {$dotCount}\n";

function add_border_to_grid(&$grid, $char) {
    array_unshift($grid, str_repeat($char, strlen($grid[0])));
    array_push($grid, str_repeat($char, strlen($grid[0])));
    foreach ($grid as $i => $row) {
        $grid[$i] = $char.$row.$char;
    }
}

function scale_up_grid($grid, $factor) {
    $scaledGrid = [];

    foreach ($grid as $row) {
        $chars = str_split($row);

        $scaledRow = [];
        foreach ($chars as $char) {
            $scaledRow[] = str_repeat($char, $factor);
        }

        for ($i = 0; $i < $factor; $i++) {
            $scaledGrid[] = implode('', $scaledRow);
        }
    }

    return $scaledGrid;
}

function print_grid($grid) {
    foreach ($grid as $row) {
        if (is_array($row)) {
            $tiles = implode(' ', $row);
        } else {
            $tiles = $row;
        }
        echo "{$tiles}\n";
    }
}

function get_next_step($currStep, $directions, $tiles) {
    $currTile = get_tile($currStep);
    #echo "Current tile {$currTile} {$currStep[0]} | {$currStep[1]}\n";
    #echo "Valid directions\n";
    #print_r($directions);
    #echo "Valid tiles\n";
    #print_r($tiles);
    #echo "\n";

    foreach ($directions as $direction) {
        switch ($direction) {
            case 'UP':
                $step = [$currStep[0] - 1, $currStep[1]];
                $stepTile = get_tile($step);
                if (in_array($stepTile, $tiles[$direction])) {
                    return $step;
                }
                break;

            case 'DOWN':
                $step = [$currStep[0] + 1, $currStep[1]];
                $stepTile = get_tile($step);
                if (in_array($stepTile, $tiles[$direction])) {
                    return $step;
                }
                break;

            case 'LEFT':
                $step = [$currStep[0], $currStep[1] - 1];
                $stepTile = get_tile($step);
                if (in_array($stepTile, $tiles[$direction])) {
                    return $step;
                }
                break;

            case 'RIGHT':
                $step = [$currStep[0], $currStep[1] + 1];
                $stepTile = get_tile($step);
                if (in_array($stepTile, $tiles[$direction])) {
                    return $step;
                }
                break;
        }
    }

    assert(false, "Step is invalid [{$currStep[0]}, {$currStep[1]}] - {$currTile}");
}

function get_valid_step_directions($coords) {
    global $grid, $validDirections, $visited;

    $directions = $validDirections[get_tile($coords)];
    foreach ($directions as $i => $direction) {
        switch ($direction) {
            case 'UP':
                $step = [$coords[0] -1, $coords[1]];
                if (!isset($grid[$step[0]][$step[1]]) || in_array($step, $visited) || !is_valid_step($step)) {
                    unset($directions[$i]);
                }
                break;

            case 'DOWN':
                $step = [$coords[0] + 1, $coords[1]];
                if (!isset($grid[$step[0]][$step[1]]) || in_array($step, $visited) || !is_valid_step($step)) {
                    unset($directions[$i]);
                }
                break;

            case 'LEFT':
                $step = [$coords[0], $coords[1] - 1];
                if (!isset($grid[$step[0]][$step[1]]) || in_array($step, $visited) || !is_valid_step($step)) {
                    unset($directions[$i]);
                }
                break;

            case 'RIGHT':
                $step = [$coords[0], $coords[1] + 1];
                if (!isset($grid[$step[0]][$step[1]]) || in_array($step, $visited) || !is_valid_step($step)) {
                    unset($directions[$i]);
                }
                break;
        }
    }

    return $directions;
}

function get_valid_tiles_for_directions($currTile, $directions) {
    global $validStepTiles;
    $tiles = [];
    foreach ($directions as $direction) {
        $tiles[$direction] = $validStepTiles[$currTile][$direction];
    }
    return $tiles;
}

function get_tile($step) {
    global $grid;
    if (!isset($grid[$step[0]][$step[1]])) {
        return 'NULL';
    }
    return $grid[$step[0]][$step[1]];
}

function is_valid_step($step) {
    $tile = get_tile($step);
    return $tile !== 'NULL' && $tile !== '.';
}

function double_resolution(array $grid): array {
    $doubledGrid = [];

    foreach ($grid as $row) {
        $doubledRow = [];
        foreach ($row as $string) {
            $doubledString = '';
            for ($i = 0; $i < strlen($string); $i++) {
                // Duplicate each character horizontally and vertically
                $doubledString .= $string[$i] . $string[$i];
            }
            // Duplicate each string vertically
            $doubledRow[] = $doubledString . $doubledString;
            $doubledRow[] = $doubledString . $doubledString;
        }
        // Add the doubled row to the grid
        $doubledGrid[] = $doubledRow;
        $doubledGrid[] = $doubledRow;
    }

    return $doubledGrid;
}

function flood_fill_grid(array &$grid, $row, $col, $from, $to) {
    $rowBreak = $row < 0 || $row >= count($grid);
    $colBreak = $col < 0 || !isset($grid[$row]) || $col >= strlen($grid[$row]);
    if ($rowBreak || $colBreak) {
        return;
    }

    if ($grid[$row][$col] !== $from) {
        return;
    }

    $grid[$row][$col] = $to;

    flood_fill_grid($grid, $row - 1, $col, $from, $to); // Right
    flood_fill_grid($grid, $row + 1, $col, $from, $to); // Left
    flood_fill_grid($grid, $row, $col - 1, $from, $to); // Down
    flood_fill_grid($grid, $row, $col + 1, $from, $to); // Up
}

function halveResolution(array $grid): array {
    $halvedGrid = [];

    // Iterate over every second row and column to reduce resolution
    for ($i = 0; $i < count($grid); $i += 2) {
        $halvedRow = [];
        for ($j = 0; $j < count($grid[$i]); $j += 2) {
            // Take the character from the top-left corner of each 2x2 block
            $halvedRow[] = $grid[$i][$j];
        }
        $halvedGrid[] = $halvedRow;
    }

    return $halvedGrid;
}
