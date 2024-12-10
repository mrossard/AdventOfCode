<?php

$grid = array_map(
    str_split(...),
    file($argv[1], FILE_IGNORE_NEW_LINES)
);

$reachableNines = [];
$todo = [];
$ratings = [];
foreach ($grid as $y => $row) {
    foreach ($row as $x => $val) {
        if ($val === '.') {
            $val = PHP_INT_MIN;
        }
        $grid[$y][$x] = (int)$val;
        if ($val !== '9') {
            continue;
        }
        $todo[9][] = [$y, $x];
        $reachableNines[$y . ',' . $x][] = $y . ',' . $x;
        $ratings[$y . ',' . $x] = 1;
    }
}

while (!empty($todo)) {
    $currentHeight = array_key_first($todo);
    $positions = array_shift($todo);
    foreach ($positions as $position) {
        //look at neighbours, if they're at $currentHeight - 1 then update their rating and reachable nines, then add them to the todo list
        foreach ([[-1, 0], [1, 0], [0, -1], [0, 1]] as $direction) {
            $x = $position[1] + $direction[1];
            $y = $position[0] + $direction[0];
            if (($grid[$y][$x] ?? PHP_INT_MAX) === $currentHeight - 1) {
                $coords = $y . ',' . $x;
                $reachableNines[$coords] = array_unique(
                    array_merge($reachableNines[$coords] ?? [], $reachableNines[$position[0] . ',' . $position[1]])
                );
                $ratings[$coords] = ($ratings[$coords] ?? 0) + $ratings[$position[0] . ',' . $position[1]];
                if (!in_array([$y, $x], $todo[$currentHeight - 1] ?? [])) {
                    $todo[$currentHeight - 1][] = [$y, $x];
                }
            }
        }
    }
}

$reachableNines = array_filter(
    $reachableNines,
    fn($coords) => $grid[explode(',', $coords)[0]][explode(',', $coords)[1]] === 0,
    ARRAY_FILTER_USE_KEY
);

$ratings = array_filter(
    $ratings,
    fn($coords) => $grid[explode(',', $coords)[0]][explode(',', $coords)[1]] === 0,
    ARRAY_FILTER_USE_KEY
);

echo 'part 1 : ', array_sum(array_map(fn($trailhead) => count($trailhead), $reachableNines)), PHP_EOL;
echo 'part 2 : ', array_sum(array_map(fn($trailhead) => $trailhead, $ratings)), PHP_EOL;
