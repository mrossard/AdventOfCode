<?php

$ranges = file($argv[1], FILE_IGNORE_NEW_LINES)[0] //une seule ligne dans l'input
        |> (function (string $input) { //ranges séparés par des virgules
            return explode(',', $input);
        })
        |> (function (array $strRanges) { // ranges sous la forme "debut-fin"
            return array_map(fn($range) => explode('-', $range), $strRanges);
        });

$maxRepeats = $argv[2] ?? PHP_INT_MAX;

function sumRepeatNumbers(array $range, int $minRepeats = 2, int $maxRepeats = PHP_INT_MAX): array
{
    $solutions = [];
    foreach (range(1, max(floor(strlen($range[1]) / 2), 1)) as $partLength) {
        $rangeStart = (int)str_pad(1, $partLength, '0', STR_PAD_RIGHT);
        $rangeEnd = (int)str_pad(9, $partLength, '9', STR_PAD_RIGHT);
        $minimumRepeats = max(ceil(strlen($range[0]) / $partLength), $minRepeats);
        $maximumRepeats = min(floor(strlen($range[1]) / $partLength), $maxRepeats);
        if ($minimumRepeats > $maximumRepeats) {
            continue;
        }
        foreach (range($rangeStart, $rangeEnd) as $part) {
            foreach (range($minimumRepeats, $maximumRepeats) as $repeats) {
                $number = (int)str_repeat($part, $repeats);
                if ($number <= $range[1] && $number >= $range[0]) {
                    $solutions[] = $number;
                }
            }
        }
    }

    return $solutions |> array_unique(...);
}

$result = $ranges
        |> (function (array $ranges) use ($maxRepeats) {
            return array_map(
                fn($range) => sumRepeatNumbers($range, 2, $maxRepeats),
                $ranges
            );
        })
        |> (function (array $solutions) {
            return array_reduce(
                array: $solutions,
                callback: function ($carry, $solution) {
                    return array_merge($carry, $solution);
                },
                initial: []
            );
        })
        |> array_unique(...)
        |> array_sum(...);

echo 'result : ', $result, PHP_EOL;