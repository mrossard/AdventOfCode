<?php

use BcMath\Number;

$input = array_map(
    fn($str) => (int)$str,
    str_split(file_get_contents($argv[1]))
);

$result = '';
$fileId = 0;
$inputSize = count($input);
$lastFileId = (int)floor($inputSize / 2) + $fileId;
while ($inputSize > 0) {
    $fileSize = $input[0];
    $lastFileSize = $input[$inputSize - 1];

    //add the file at the start
    foreach (range(0, $fileSize - 1) as $i) {
        $result .= $fileId;
    }
    $fileId++;
    array_shift($input);
    $inputSize--;

    //fill in the space with the file at the end
    if ($inputSize == 0)
        break;
    $freeSpaces = $input[0];
    $lastFilePosition = $inputSize - 1;
    $fileSize = $input[$lastFilePosition];
    while ($freeSpaces > 0) {
        if ($fileSize > $freeSpaces) {
            foreach (range(0, $freeSpaces - 1) as $i) {
                $result .= $lastFileId;
            }
            $input[$lastFilePosition] -= $freeSpaces;
            $freeSpaces = 0;
        } else {
            foreach (range(0, $fileSize - 1) as $i) {
                $result .= $lastFileId;
            }
            array_pop($input);
            array_pop($input);
            $inputSize -= 2;
            if ($inputSize == 0) {
                break;
            }
            $lastFilePosition = $inputSize - 1;
            $freeSpaces -= $fileSize;
            $lastFileId--;
            $fileSize = $input[$lastFilePosition];
        }
    }
    array_shift($input);
    $inputSize--;
}

$sum = new Number(0);

foreach (str_split($result) as $i => $char) {
    $mul = new Number($char);
    $sum = $sum->add($mul->mul($i));
}
echo 'part 1 result: ' . $sum, PHP_EOL;

