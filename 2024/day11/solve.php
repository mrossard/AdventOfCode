<?php

$input = array_map(fn($str) => (int)$str, explode(' ', file($argv[1], FILE_IGNORE_NEW_LINES)[0]));

function blink(int $stone, array &$precalculated, int $remainingBlinks): array
{
    if (($precalculated[$stone][$remainingBlinks] ?? null) !== null) {
        return $precalculated[$stone][$remainingBlinks];
    }

    if ($stone === 0) {
        $result = [1];
    } else {
        $digits = strlen($stone);
        if ($digits % 2 === 0) {
            $result = [(int)substr($stone, 0, $digits / 2), (int)substr($stone, $digits / 2)];
        } else {
            $result = [$stone * 2024];
        }
    }

    if ($remainingBlinks > 1) {
        $copy = $result;
        $result = [];
        foreach ($copy as $res) {
            $result = array_merge($result, blink($res, $precalculated, $remainingBlinks - 1));
        }
    }
    $precalculated[$stone][$remainingBlinks] = $result;

    return $result;
}

$precomputed = [];
$results = array_map(
    fn($stone) => blink($stone, $precomputed, $argv[2]),
    $input
);
$result = array_merge(...$results);
echo 'part 1 : ' . count($result) . PHP_EOL;