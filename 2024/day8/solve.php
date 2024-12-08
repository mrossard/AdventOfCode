<?php

$grid = array_map(
    str_split(...),
    file($argv[1], FILE_IGNORE_NEW_LINES)
);

$part2 = ($argv[2] ?? '1') === '2';

$frequencies = [];
foreach ($grid as $y => $row) {
    foreach ($row as $x => $val) {
        if ($val === '.') {
            continue;
        }
        $frequencies[$val][] = [$x, $y];
    }
}

$maxX = count($grid[0]);
$maxY = count($grid);

function antinodes($antenna1, $antenna2, int $maxX, int $maxY, $part2 = false): array
{
    $distanceX = $antenna1[0] - $antenna2[0];
    $distanceY = $antenna1[1] - $antenna2[1];

    $antinodes = [];

    if ($part2) {
        $antinodes[] = $antenna1;
        $antinodes[] = $antenna2;
    }

    //direction 1
    $nextX = $antenna1[0] + $distanceX;
    $nextY = $antenna1[1] + $distanceY;
    $iterations = 1;
    while ($nextX >= 0 && $nextX < $maxX && $nextY >= 0 && $nextY < $maxY && ($part2 || $iterations++ < 2)) {
        $antinodes[] = [$nextX, $nextY];
        $nextX += $distanceX;
        $nextY += $distanceY;
        $iterations++;
    }
    //direction 2
    $nextX = $antenna2[0] - $distanceX;
    $nextY = $antenna2[1] - $distanceY;
    $iterations = 1;
    while ($nextX >= 0 && $nextX < $maxX && $nextY >= 0 && $nextY < $maxY && ($part2 || $iterations++ < 2)) {
        $antinodes[] = [$nextX, $nextY];
        $nextX -= $distanceX;
        $nextY -= $distanceY;
        $iterations++;
    }

    return $antinodes;
}

$antinodes = [];

foreach ($frequencies as $frequency => $antennas) {
    foreach ($antennas as $antenna1) {
        foreach ($antennas as $antenna2) {
            if ($antenna1 === $antenna2) {
                continue;
            }
            $antinodes = array_merge($antinodes, antinodes($antenna1, $antenna2, $maxX, $maxY, $part2));
        }
    }
}

$antinodes = array_unique(
    array_map(
        fn($node) => $node[0] . ',' . $node[1],
        $antinodes
    )
);

echo 'result : ', count($antinodes), PHP_EOL;