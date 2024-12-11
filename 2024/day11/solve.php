<?php

$input = array_map(fn($str) => (int)$str, explode(' ', file($argv[1], FILE_IGNORE_NEW_LINES)[0]));

function blink(int $stone, array &$precomputed, int $remainingBlinks): int
{
    if (($precomputed[$stone][$remainingBlinks] ?? null) !== null) {
        return $precomputed[$stone][$remainingBlinks];
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
        $nbStones = 0;
        foreach ($result as $res) {
            $nbStones += blink($res, $precomputed, $remainingBlinks - 1);
        }
    } else {
        $nbStones = count($result);
    }
    $precomputed[$stone][$remainingBlinks] = $nbStones;

    return $nbStones;
}

$precomputed = [];
$results = array_map(
    fn($stone) => blink($stone, $precomputed, $argv[2]),
    $input
);
$result = array_sum($results);
echo 'result : ' . $result . PHP_EOL;