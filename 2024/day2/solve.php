<?php

$input = file($argv[1], FILE_IGNORE_NEW_LINES);

$reports = array_map(
    fn($line) => array_map(fn($str) => (int)$str, explode(' ', $line)),
    $input
);

$safeFunctionPart1 = function (array $report) {
    if (count($report) < 2)
        return true;
    return
        (
            array_all(
                range(1, count($report) - 1),
                fn($i) => $report[$i] > $report[$i - 1]
            ) ||
            array_all(
                range(1, count($report) - 1),
                fn($i) => $report[$i] < $report[$i - 1]
            )
        ) &&
        !array_any(
            range(1, count($report) - 1),
            fn($i) => abs($report[$i] - $report[$i - 1]) > 3
        );
};

$safeFunctionPart2 = function (array $report) use ($safeFunctionPart1) {

    if ($safeFunctionPart1($report)) {
        return true;
    }

    foreach (range(0, count($report) - 1) as $i) {
        $newReport = $report;
        array_splice($newReport, $i, 1);

        if ($safeFunctionPart1($newReport)) {
            return true;
        }
    }

    return false;
};

echo 'part 1 result: ' . count(array_filter($reports, $safeFunctionPart1)) . PHP_EOL;
echo 'part 2 result: ' . count(array_filter($reports, $safeFunctionPart2)) . PHP_EOL;

