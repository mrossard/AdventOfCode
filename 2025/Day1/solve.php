<?php


$part1 = file($argv[1], FILE_IGNORE_NEW_LINES)
        |> (function ($lines) {
                return array_map(fn($line) => [substr($line, 0,1), (int)substr($line,1)], $lines);
            })
        |> (function ($movements) {
                return array_reduce(
                    $movements,
                    function($positions, $movement){
                        $positions[] = (match($movement[0]){
                            "L" => array_last($positions) - $movement[1],
                            "R" => array_last($positions) + $movement[1],
                        }) % 100;
                        return $positions;
                    },
                    [50]
                );
            })
        |> (function($positions) { return array_filter($positions, fn($value) => $value === 0);})
        |> count(...);


echo 'part 1 : ', $part1, PHP_EOL, PHP_EOL;

$part2 = file($argv[1], FILE_IGNORE_NEW_LINES)
        |> (function ($lines) {
            return array_map(fn($line) => [substr($line, 0,1), (int)substr($line,1)], $lines);
        })
        |> (function ($movements) {
            return array_reduce(
                $movements,
                function($positions, $movement){
                    $previousPosition = array_last($positions);
                    $nextPosition = (match($movement[0]){
                            "L" => $previousPosition['position'] - $movement[1],
                            "R" => $previousPosition['position'] + $movement[1],
                        });

                    $clicksToZero = floor(abs($nextPosition / 100));
                    if($nextPosition <= 0 && $previousPosition['position'] > 0){
                        $clicksToZero++;
                    }

                    $nextPosition %= 100;
                    if($nextPosition < 0){
                        $nextPosition += 100;
                    }

                    $positions[] = [
                        'position' => $nextPosition,
                        'clicksToZero' => $clicksToZero
                    ];
                    return $positions;
                },
                [['position' => 50, 'clicksToZero' => 0]]
            );
        })
        |> (function($positions){
                return array_map(
                        fn($position) => $position['clicksToZero'],
                        $positions
                );
        })
        |> array_sum(...);


echo 'part 2 : ', $part2, PHP_EOL;