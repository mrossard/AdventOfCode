<?php

$numbers = file($argv[1], FILE_IGNORE_NEW_LINES)
    |> (function($lines) { return array_map(fn($line) => explode(' ', $line), $lines); })
    |> (function($lines) {
        return array_map(
            function($line){
                return array_reduce(
                    $line,
                    fn($carry, $item) => $item != '' ? [...$carry, $item] : $carry,
                    []
                );
            },
            $lines);
    });

$operations = array_pop($numbers);

$part1 = $numbers |>
        (function($numbers) use ($operations){
            return array_reduce(
                $numbers,
                function($carry, $line) use ($operations) {
                  foreach($line as $column => $number){
                        $carry[$column] = match($operations[$column]) {
                            '*' => $carry[$column] * (int) $number,
                            default => $carry[$column] + (int) $number
                        };
                  }
                  return $carry;
                },
                array_map(fn($operation) => match($operation){ '*' => 1, default => 0}, $operations)
            );
        })
        |>array_sum(...);

echo 'part 1 : ', $part1, PHP_EOL;

//part 2 : les espaces sont importants !
$numbers = file($argv[1], FILE_IGNORE_NEW_LINES)
    |> (function($lines) { return array_map(str_split(...), $lines); });

$operations = array_pop($numbers);
$positions = [...array_keys($operations, '*'), ...array_keys($operations, '+')];
sort($positions);

// on reconstruit les nombres correctement
$numbers = array_reduce(
    $numbers,
    function($carry, $line) use ($positions) {
        foreach($line as $column => $char){
            $carry[$column] = ($carry[$column] ?? '').$char;
        }
        return $carry;
    },
    []
);

$part2 = $positions
    |> (function($positions) use ($numbers, $operations){
            $current = 0;
            $result = array_map(fn($position) => match($operations[$position]){ '*' => 1, default => 0}, $positions);
            foreach($positions as $position){
                $positionLine = array_search($position, $positions);
                while(trim($numbers[$current] ?? '') != ''){
                    $result[$positionLine] = match($operations[$position]){
                        '*' => $result[$positionLine] * (int) trim($numbers[$current]),
                        default => $result[$positionLine] + (int) trim($numbers[$current])
                    };
                    $current++;
                }
                $current++;
            }
            return $result;
        });

echo 'part 2 : ', array_sum($part2), PHP_EOL;