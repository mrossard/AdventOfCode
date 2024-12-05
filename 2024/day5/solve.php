<?php

$input = file($argv[1], FILE_IGNORE_NEW_LINES);

//read input
$section = 'rules';
$rules = [];
$updates = [];
foreach ($input as $line) {
    if ($line == '') {
        $section = 'updates';
        continue;
    }
    if ($section == 'rules') {
        $rules[] = $line;
    } else {
        $updates[] = array_map(fn($str) => (int)$str, explode(',', $line));
    }
}

//sort
$sort = function (int $page1, int $page2) use ($rules): int {
    if (in_array($page1 . '|' . $page2, $rules)) {
        return -1;
    }
    if (in_array($page2 . '|' . $page1, $rules)) {
        return 1;
    }
    return 0;
};

$sortedUpdates = array_map(
    function ($update) use ($sort) {
        usort($update, $sort);
        return $update;
    },
    $updates
);

//compare
$correctUpdates = array_intersect(
    array_map(fn($update) => implode(',', $update), $updates),
    array_map(fn($update) => implode(',', $update), $sortedUpdates),
);
$incorrectUpdates = array_diff(
    array_map(fn($update) => implode(',', $update), $sortedUpdates),
    array_map(fn($update) => implode(',', $update), $updates),
);

//result
$part1 = array_sum(
    array_map(
        function ($update) {
            $values = explode(',', $update);
            return (int)($values[(count($values) - 1) / 2]);
        },
        $correctUpdates
    )
);

$part2 = array_sum(
    array_map(
        function ($update) {
            $values = explode(',', $update);
            return (int)($values[(count($values) - 1) / 2]);
        },
        $incorrectUpdates
    )
);


echo 'part 1 : ', $part1, PHP_EOL;
echo 'part 2 : ', $part2, PHP_EOL;