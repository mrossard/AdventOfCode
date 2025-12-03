<?php


$banks = file($argv[1], FILE_IGNORE_NEW_LINES);

$numberOfBatteries = $argv[2] ?? 2;

function findMax(string $bank): int
{
    $maxValue = $bank
        |> str_split(...)
        |> max(...);

    return strpos($bank, ''.$maxValue);
}

$result = $banks
    |> function($banks) use ($numberOfBatteries){
            return array_map(
                callback: function(string $bank) use($numberOfBatteries) {
                    $currentChar = 0;
                    $previousPosition = -1;
                    $chars = [];
                    while($currentChar < $numberOfBatteries) {
                        $length = 0 - ($numberOfBatteries - $currentChar) + 1;
                        if($length == 0){
                            $length = null;
                        }
                        $maxChar = findMax(
                            substr(
                                $bank,
                                $previousPosition + 1,
                                $length
                            )
                            ) + $previousPosition + 1;
                        $chars[] = $bank[$maxChar];
                        $currentChar++;
                        $previousPosition = $maxChar;
                    }
                    return (int) implode('', $chars);
                },
                array: $banks
            );
    }
    |> array_sum(...);

echo 'result : ', $result, PHP_EOL;