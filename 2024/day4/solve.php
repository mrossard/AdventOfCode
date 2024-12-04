<?php

$grid = array_map(
    fn($line) => str_split($line),
    file($argv[1], FILE_IGNORE_NEW_LINES)
);

function letterPositions(string $letter, array $grid): array
{
    $positions = [];
    foreach ($grid as $y => $row) {
        foreach ($row as $x => $val) {
            if ($val === $letter) {
                $positions[] = [$x, $y];
            }
        }
    }
    return $positions;
}

function isWordFound($word, $grid, $from, $direction): bool
{
    $current = $from;
    foreach (str_split($word) as $letter) {
        if (($grid[$current[1]][$current[0]] ?? null) !== $letter) {
            return false;
        }
        $current = [$current[0] + $direction[0], $current[1] + $direction[1]];
    }
    return true;
}

function countWords(string $word, array $grid): int
{
    $firstLetter = $word[0];
    $positions = letterPositions($firstLetter, $grid);
    $found = 0;
    foreach ($positions as $position) {
        //look for following letters in every direction
        foreach ([[-1, 0], [1, 0], [0, -1], [0, 1], [-1, -1], [1, 1], [-1, 1], [1, -1]] as $direction) {
            if (isWordFound($word, $grid, $position, $direction)) {
                //echo 'Found at ', $position[1], ',', $position[0], ' direction ', $direction[1], ',', $direction[0], PHP_EOL;
                $found++;
            }
        }
    }
    return $found;
}

function countXMAS(array $grid)
{
    $positions = letterPositions('A', $grid);
    $found = 0;
    foreach ($positions as $position) {
        //is 'MAS' found diagonally in both directions using this 'A' ?
        if ((isWordFound('MAS', $grid, [$position[0] + 1, $position[1] + 1], [-1, -1])
                || isWordFound('MAS', $grid, [$position[0] - 1, $position[1] - 1], [1, 1])) &&
            (isWordFound('MAS', $grid, [$position[0] + 1, $position[1] - 1], [-1, 1])
                || isWordFound('MAS', $grid, [$position[0] - 1, $position[1] + 1], [1, -1]))) {
            $found++;
        }
    }
    return $found;
}

echo 'part 1 : ', countWords('XMAS', $grid) . PHP_EOL;
echo 'part 2 : ', countXMAS($grid) . PHP_EOL;

