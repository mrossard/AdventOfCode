<?php

$boxes = file($argv[1], FILE_IGNORE_NEW_LINES)
    |> (function($lines) {return array_map(fn($line) => explode(',', $line), $lines);})
    |> (function($coordinates) {
        return array_map(
            fn($line) => array_map(intval(...), $line),
            $coordinates
        );
    });

// calcul des distances, tri croissant
$distances = [];

for ($first = 0; $first < count($boxes) - 1; $first++) {
    for($second = $first + 1; $second < count($boxes); $second++) {
        $distances[$first.'->'.$second] = ($boxes[$first][0] - $boxes[$second][0]) ** 2
            + ($boxes[$first][1] - $boxes[$second][1]) ** 2
            + ($boxes[$first][2] - $boxes[$second][2]) ** 2;
    }
}
asort($distances);

// calcul des circuits en fonction du nombre de connexions demandées
$connectionsToMake = $argv[2] ?? PHP_INT_MAX;
$relevantDistances = array_slice($distances, 0, $connectionsToMake);

$circuits = [];
foreach($relevantDistances as $boxIds => $distance) {
    if(count($circuits) == 1 && count(array_first($circuits)) === count($boxes)){
        break;
    }
    [$first, $second] = explode('->', $boxIds);
    $previousBoxes = [$first, $second]; //pour la partie 2, flemme de mettre la condition de sortie au bon endroit
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
    if($firstCircuit === $secondCircuit){
        continue;
    }
    //on merge les deux
    $circuits[$firstCircuit] = array_merge($circuits[$firstCircuit], $circuits[$secondCircuit]);
    unset($circuits[$secondCircuit]);
}

if($connectionsToMake !== PHP_INT_MAX){
    $circuitLengths = array_map(count(...), $circuits);
    rsort($circuitLengths);
    $top3 = array_slice($circuitLengths, 0, 3)
            |> array_product(...);

    echo 'part 1 result: ' . $top3 . PHP_EOL;
}
else {
    echo 'part 2 result: '.$boxes[$previousBoxes[0]][0] * $boxes[$previousBoxes[1]][0].PHP_EOL;
}