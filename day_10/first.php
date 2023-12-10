<?php

$input = file_get_contents('input.txt');
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
$displayGrid = $grid;



$visited = [];
$currStep = $startStep;
$stepCount = 1;
while (true) {
    $visited[] = $currStep;

    $displayGrid[$currStep[0]][$currStep[1]] = 'X';
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

$res = $stepCount / 2;
echo $stepCount."\n";
echo $tileCount.PHP_EOL;
echo $tileCount - $stepCount.PHP_EOL;
echo "How many steps along the loop does it take to get to the point farthest from the starting position? {$res}\n";

$x2Grid = doubleResolutionWithBorder($displayGrid);

foreach ($x2Grid as $i => $row) {
    foreach ($row as $j => $tile) {
        $x2Grid[$i][$j] = str_replace(['|', '-', 'L', 'J', '7', 'F'], '.', $tile);
    }
}
#print_r($x2Grid);
#exit;

echo "Double resolution grid before flooding\n";
foreach ($x2Grid as $i => $row) {
    $tiles = implode(' ', $row);
    echo "{$tiles}\n";
}

floodFill($x2Grid, 1, 1, ".", " ");

$x2Grid = halveResolution($x2Grid);

echo "Original resolution grid after flooding\n";
foreach ($x2Grid as $row) {
    $tiles = implode(' ', $row);
    echo "{$tiles}\n";
}

$dotCount = 0;
foreach ($x2Grid as $row) {
    foreach ($row as $tile) {
        if ($tile === '....') {
            $dotCount++;
        }
    }
}
echo "Dots surrounded by loop: {$dotCount}\n";

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

function floodFill(array &$grid, $row, $col, $oldValue, $newValue) {
    if (
        $row < 0 || $row >= count($grid) ||
        $col < 0 || $col >= count($grid[$row]) ||
        strpos($grid[$row][$col], $oldValue) === false
    ) {
        return;
    }

    $grid[$row][$col] = $newValue;

    // Recursively fill in all directions
    floodFill($grid, $row - 1, $col, $oldValue, $newValue); // Up
    floodFill($grid, $row + 1, $col, $oldValue, $newValue); // Down
    floodFill($grid, $row, $col - 1, $oldValue, $newValue); // Left
    floodFill($grid, $row, $col + 1, $oldValue, $newValue); // Right
}

function doubleResolutionWithBorder(array $grid): array {
    $rows = count($grid);
    $cols = count($grid[0]);

    // Create a new grid with a border
    $doubledGrid = [];

    // Add top border
    $doubledGrid[] = array_fill(0, 2 * $cols + 2, '.');
    
    foreach ($grid as $row) {
        $doubledRow = [];
        
        // Add left border
        $doubledRow[] = '.';
        
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

        // Add right border
        $doubledRow[] = '.';
        
        // Add the doubled row to the grid
        $doubledGrid[] = $doubledRow;
        $doubledGrid[] = $doubledRow;
    }

    // Add bottom border
    $doubledGrid[] = array_fill(0, 2 * $cols + 2, '.');

    return $doubledGrid;
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
