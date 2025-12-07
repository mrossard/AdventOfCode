<?php

$manifold = file( $argv[1], FILE_IGNORE_NEW_LINES)
    |> (function($lines) { return array_map(fn($line) => str_split($line), $lines); });

function goDown(array $manifold, int $currentY, array $currentXPositions, int &$totalSplits): array
{
    $nextXPositions = [];
    foreach($currentXPositions as $x => $timelinesToX) {
        switch($manifold[$currentY + 1][$x] ?? '.') {
            case '.' :
                $nextXPositions[$x] = ($nextXPositions[$x] ?? 0) + $timelinesToX;
                break;
            case '^':
                $nextXPositions[$x - 1] = ($nextXPositions[$x-1] ?? 0) + $timelinesToX;
                $nextXPositions[$x + 1] = ($nextXPositions[$x+1] ?? 0) + $timelinesToX;
                $totalSplits++;
                break;
            default:
                break;
        }
    }
    return $nextXPositions;
}

$xPositions = [array_search('S', $manifold[0]) => 1];
$currentY = 1;
$totalSplits = 0;
while($currentY < count($manifold)) {
    $xPositions = goDown($manifold, $currentY, $xPositions, $totalSplits);
    $currentY++;
}

echo  'totalSplits : ', $totalSplits, PHP_EOL, 'timelines : ', array_sum($xPositions) . PHP_EOL;

