<?php

use BcMath\Number;

$operations = array_map(
    fn($parts) => [
        'result' => new Number($parts[0]),
        'parts' => array_map(
            fn($str) => new Number($str),
            array_map(
                fn($str) => new Number($str),
                explode(' ', $parts[1])
            )
        )
    ],
    array_map(
        fn($line) => explode(': ', $line),
        file($argv[1], FILE_IGNORE_NEW_LINES)
    )
);

$withConcat = (($argv[2] ?? '0') === '1');

$isValidOperation = function (array $operation) use ($withConcat): bool {
    $currentState = [$operation['parts'][0]];
    foreach (range(1, count($operation['parts']) - 1) as $i) {
        $nextState = [];
        foreach ($currentState as $total) {
            $totalAdd = $total + $operation['parts'][$i];
            $totalMul = $total * $operation['parts'][$i];

            if ($totalAdd <= $operation['result']) {
                $nextState[] = $totalAdd;
            }
            if ($totalMul <= $operation['result']) {
                $nextState[] = $totalMul;
            }
            if ($withConcat) {
                $totalConcat = new Number($total . $operation['parts'][$i]);
                if ($totalConcat <= $operation['result']) {
                    $nextState[] = $totalConcat;
                }
            }
        }
        if (empty($nextState)) {
            return false;
        }
        $currentState = $nextState;
    }

    if (in_array($operation['result'], $nextState)) {
        return true;
    }
    return false;
};

$operations = array_filter(
    $operations,
    $isValidOperation
);

echo 'result : ', array_reduce(
    array_map(
        fn($operation) => $operation['result'],
        $operations
    ),
    function ($carry, BcMath\Number $number) {
        return $carry + $number;
    },
    0
), PHP_EOL;
