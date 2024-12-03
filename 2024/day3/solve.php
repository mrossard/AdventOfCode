<?php

$input = file($argv[1], FILE_IGNORE_NEW_LINES);

$input = implode('', $input);

$parts = array_filter(
    array_map(
        function ($part) {
            $parts = explode(',', $part);
            return [
                $parts[0],
                substr($parts[1], 0, strpos($parts[1], ')'))
            ];
        },

        array_filter(explode('mul(', $input),
            fn($part) => str_contains($part, ')') && str_contains($part, ',')
        )
    ),
    fn($ops) => is_numeric($ops[0]) && is_numeric($ops[1])
);

$sum = array_reduce($parts, function ($carry, $part) {
    return $carry + (int)$part[0] * (int)$part[1];
}, 0);

echo 'part 1 : ' . $sum . PHP_EOL;

// let's ignore operations possibly appearing twice
$shouldDo = function ($input, $part): bool {
    $operation = 'mul(' . $part[0] . ',' . $part[1] . ')';
    $position = strpos($input, $operation);
    $substr = substr($input, 0, $position);
    $lastDoBeforeOperation = strrpos($substr, 'do()');
    $lastDontBeforeOperation = strrpos($substr, "don't()");

    return $lastDoBeforeOperation >= $lastDontBeforeOperation;
};

$sumpart2 = array_reduce(array_filter($parts, fn($part) => $shouldDo($input, $part)), function ($carry, $part) {
    return $carry + (int)$part[0] * (int)$part[1];
}, 0);

echo 'part 2 : ' . $sumpart2 . PHP_EOL;