<?php

$input = file('input.txt', FILE_IGNORE_NEW_LINES);

$lists = array_reduce(
    $input,
    function ($carry, $line) {
        [$first, $second] = explode('   ', $line);
        $carry[0][] = (int)$first;
        $carry[1][] = (int)$second;
        return $carry;
    },
    [[], []]
);

sort($lists[0]);
sort($lists[1]);

$distances = array_map(
    function ($i) use ($lists) {
        return abs($lists[0][$i] - $lists[1][$i]);
    },
    range(0, count($lists[0]) - 1)
);

echo 'part 1 result: ' . array_sum($distances) . PHP_EOL;

$similarities = array_map(
    function ($val) use ($lists) {
        return $val * count(array_filter($lists[1], fn($val2) => $val2 === $val));
    },
    $lists[0]
);

echo 'part 2 result: ' . array_sum($similarities) . PHP_EOL;