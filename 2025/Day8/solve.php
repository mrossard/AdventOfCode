<?php

$boxes = file($argv[1], FILE_IGNORE_NEW_LINES)
    |> (function($lines) {return array_map(fn($line) => explode(',', $line), $lines);})
    |> (function($coordinates) {
        return array_map(
            fn($line) => array_map(intval(...), $line),
            $coordinates
        );
    });


$distances = [];

for ($first = 0; $first < count($boxes) - 1; $first++) {
    for($second = $first + 1; $second < count($boxes); $second++) {
        $distances[$first.'->'.$second] = ($boxes[$first][0] - $boxes[$second][0]) * ($boxes[$first][0] - $boxes[$second][0])
            + ($boxes[$first][1] - $boxes[$second][1]) * ($boxes[$first][1] - $boxes[$second][1])
            + ($boxes[$first][2] - $boxes[$second][2]) * ($boxes[$first][2] - $boxes[$second][2]);
    }
}

asort($distances);

$circuits = [];

$connectionsToMake = $argv[2] ?? PHP_INT_MAX;

$relevantDistances = array_slice($distances, 0, $connectionsToMake);

foreach($relevantDistances as $boxIds => $distance) {
    if(count($circuits) == 1 && count(array_first($circuits)) === count($boxes)){
        break;
    }
    [$first, $second] = explode('->', $boxIds);
    $previousBoxes = [$first, $second];
    $firstCircuit = null;
    $secondCircuit = null;
    foreach($circuits as $circuitId => $circuit){
        if(in_array($first, $circuit)){
            $firstCircuit = $circuitId;
        }
        if(in_array($second, $circuit)){
            $secondCircuit = $circuitId;
        }
    }
    //nouveau circuit
    if($firstCircuit === null && $secondCircuit === null){
        $circuits[] = [$first, $second];
        continue;
    }
    //existant
    if($firstCircuit === null){
        $circuits[$secondCircuit][] = $first;
        continue;
    }
    if($secondCircuit === null){
        $circuits[$firstCircuit][] = $second;
        continue;
    }
    //on merge les deux
    if($firstCircuit === $secondCircuit){
        continue;
    }
    $circuits[$firstCircuit] = array_merge($circuits[$firstCircuit], $circuits[$secondCircuit]);
    unset($circuits[$secondCircuit]);
}


$circuitLengths = array_map(count(...), $circuits);

rsort($circuitLengths);

$top3 = array_slice($circuitLengths, 0, 3)
    |> ( function($lengths) {
        return array_reduce(
            $lengths,
            fn($carry, $length) => $carry * $length,
            1
        );
    });

echo 'part 1 result: ' . $top3 . PHP_EOL;
echo 'part 2 result: ' . $boxes[$previousBoxes[0]][0] * $boxes[$previousBoxes[1]][0]  . PHP_EOL;