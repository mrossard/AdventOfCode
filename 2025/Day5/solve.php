<?php

$data = file($argv[1], FILE_IGNORE_NEW_LINES)
    |> (function($lines){
        return array_reduce(
            $lines,
            function($carry, $line) {
                if (empty($line)) {
                    $carry['ingredients'] = [];
                    return $carry;
                }
                if(array_key_exists('ingredients', $carry)){
                    $carry['ingredients'][] = (int) $line;
                }
                else{
                    $carry['freshRanges'][] = array_map(intval(...), explode('-', $line));
                }
                return $carry;
            },
            ['freshRanges' => []]
        );
    });

$freshIngredients = array_filter($data['ingredients'],
    fn($id) => array_any($data['freshRanges'], fn($range) => $id >= $range[0] && $id <= $range[1])
);

echo 'part 1 result: ' . count($freshIngredients) . PHP_EOL;

$betterRanges = array_reduce(
    $data['freshRanges'],
    function($carry, $range){
        foreach($carry as $rangeId => $otherRange){
            if(($otherRange[0] >= $range[0] && $otherRange[0] <= $range[1]) ||
                ($range[0] >= $otherRange[0] && $range[0] <= $otherRange[1])){
                $range = [min($otherRange[0], $range[0]), max($otherRange[1], $range[1])];
                unset($carry[$rangeId]);
            }
        }

        return [...$carry, $range];
    },
    []
);

$freshIds = $betterRanges
    |> (function($ranges){
            return array_map(fn($range) => $range[1] - $range[0] + 1, $ranges);
        })
    |> array_sum(...);

echo "part 2 result: " . $freshIds . PHP_EOL;