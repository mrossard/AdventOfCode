<?php

$grid = array_map(
    str_split(...),
    file($argv[1], FILE_IGNORE_NEW_LINES)
);

/**
 * Parcours "par région"
 * on part de 0,0 et on regarde les voisins : si même région, on ajoute, sinon on met dans la pile à explorer
 */
$todo = ['0,0' => [0, 0]];
$regions = [];
$explored = [];
while (!empty($todo)) {
    $coords = array_shift($todo);
    $currentPlant = $grid[$coords[0]][$coords[1]];
    $currentRegion = [$coords[0] . ',' . $coords[1] => $coords];
    $unExploredInRegion = [$coords];
    $currentPerimeter = 0;
    while (!empty($unExploredInRegion)) {
        $coords = array_shift($unExploredInRegion);
        foreach ([[-1, 0], [1, 0], [0, -1], [0, 1]] as $direction) {
            $y = (int)$coords[0] + $direction[0];
            $x = (int)$coords[1] + $direction[1];
            if (($grid[$y][$x] ?? null) === null) {
                $currentPerimeter++;
                continue;
            }
            $coordsKey = $y . ',' . $x;
            $alreadyExplored = $explored[$coordsKey] ?? false;

            if ($grid[$y][$x] === $currentPlant) {
                //add it to the region and the unexplored points if necessary
                if (!$alreadyExplored) {
                    $currentRegion[$coordsKey] = [$y, $x];
                    if (!in_array([$y, $x], $unExploredInRegion)) {
                        $unExploredInRegion[] = [$y, $x];
                    }
                }
            } else {
                //add it to the queue and add 1 to the region perimeter
                if (!in_array([$y, $x], $todo) && !$alreadyExplored) {
                    $todo[$coordsKey] = [$y, $x];
                }
                $currentPerimeter++;
            }
        }
        $explored[$coords[0] . ',' . $coords[1]] = true;
        if (array_key_exists($coords[0] . ',' . $coords[1], $todo)) {
            unset($todo[$coords[0] . ',' . $coords[1]]);
        }
    }
    $regions[] = ['plant' => $currentPlant, 'perimeter' => $currentPerimeter, 'area' => count($currentRegion), 'points' => $currentRegion];
}

echo 'part 1 : ', array_sum(array_map(fn($region) => $region['perimeter'] * $region['area'], $regions)), PHP_EOL;

function countSides(array $region): int
{
    $sides = [];
    //on repère les voisins qui ne font pas partie de la région, regroupés par valeur de Y/X + par quel coté ils sont voisins
    foreach ($region as $point) {
        $top = [$point[0] - 1, $point[1]];
        $bottom = [$point[0] + 1, $point[1]];
        $left = [$point[0], $point[1] - 1];
        $right = [$point[0], $point[1] + 1];
        if (!in_array($top, $region)) {
            $sides['y'][$point[0] - 1][$point[0]][$point[1]] = $point[1];
        }
        if (!in_array($bottom, $region)) {
            $sides['y'][$point[0] + 1][$point[0]][$point[1]] = $point[1];
        }
        if (!in_array($left, $region)) {
            $sides['x'][$point[1] - 1][$point[1]][$point[0]] = $point[0];
        }
        if (!in_array($right, $region)) {
            $sides['x'][$point[1] + 1][$point[1]][$point[0]] = $point[0];
        }
    }

    //reste à compter les segments pour chaque valeur de x/y
    $sideNumber = 0;
    foreach ($sides as $xy) {
        foreach ($xy as $vals) {
            foreach ($vals as $values) {
                sort($values);
                $sideNumber++;
                $current = array_shift($values);
                while (!empty($values)) {
                    $next = array_shift($values);
                    if ($next > $current + 1) {
                        $sideNumber++;
                    }
                    $current = $next;
                }
            }
        }
    }
    return $sideNumber;
}

echo 'part 2 : ', array_sum(array_map(fn($region) => countSides($region['points']) * $region['area'], $regions)), PHP_EOL;