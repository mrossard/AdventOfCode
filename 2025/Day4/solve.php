<?php

$rolls = file($argv[1], FILE_IGNORE_NEW_LINES)
    |> (function($lines) {return array_map(fn($line) => str_split($line), $lines);});

function reachableRolls(array $rolls): array
{
    $reachable = [];
    foreach ($rolls as $y => $line) {
        foreach ($line as $x => $position) {
            if ($position !== '@') {
                continue;
            }

            $neighbours = 0;
            foreach (range(-1, 1) as $offsetY) {
                foreach (range(-1, 1) as $offsetX) {
                    if (($rolls[$y + $offsetY][$x + $offsetX] ?? '.') === '@') {
                        $neighbours++;
                    }
                }
            }
            if ($neighbours < 5) {
                $reachable[] = [$y, $x];
            }
        }
    }
    return $reachable;
}


echo 'part1 : ', count(reachableRolls($rolls)), PHP_EOL;

$totalReachable = 0;
do {
   $reachableThisTurn = reachableRolls($rolls);
    foreach ($reachableThisTurn as $position) {
        $rolls[$position[0]][$position[1]] = '.';
    }
    $totalReachable += count($reachableThisTurn);
}
while (count($reachableThisTurn) > 0);


echo 'part2 : ', $totalReachable, PHP_EOL;