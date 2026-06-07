<?php

function move(array $grid, int $row, int $col, string $direction, int $steps): ?array
{
    // tentukan delta row/col berdasarkan direction
    $deltaRow = 0;
    $deltaCol = 0;

    switch ($direction) {
        case 'N':
            $deltaRow = -1;
            break;
        case 'S':
            $deltaRow = 1;
            break;
        case 'E':
            $deltaCol = 1;
            break;
    }

    // loop steps kali, cek apakah posisi baru adalah # atau out of bounds
    for ($i = 0; $i < $steps; $i++) {
        $newRow = $row + $deltaRow;
        $newCol = $col + $deltaCol;

        // cek out of bounds
        if ($newRow < 0 || $newRow >= count($grid) || $newCol < 0 || $newCol >= count($grid[0])) {
            return null;
        }

        // cek apakah posisi baru adalah #
        if ($grid[$newRow][$newCol] === '#') {
            return null;
        }

        $row = $newRow;
        $col = $newCol;
    }

    // kalau valid sampai akhir, return ['row' => $row, 'col' => $col]
    return ['row' => $row, 'col' => $col];
}

$grid = [
    ['#', '#', '#', '#', '#', '#', '#', '#'],
    ['#', '.', '.', '.', '.', '.', '.', '#'],
    ['#', '.', '#', '#', '#', '.', '.', '#'],
    ['#', '.', '.', '.', '#', '.', '#', '#'],
    ['#', 'X', '#', '.', '.', '.', '.', '#'],
    ['#', '#', '#', '#', '#', '#', '#', '#'],
];

$startRow = 0;
$startCol = 0;

foreach ($grid as $r => $cols) {
    foreach ($cols as $c => $cell) {
        if ($cell === 'X') {
            $startRow = $r;
            $startCol = $c;
        }
    }
}

$probablePoints = [];
$maxSteps = 10;

for ($a = 1; $a <= $maxSteps; $a++) {
    for ($b = 1; $b <= $maxSteps; $b++) {
        for ($c = 1; $c <= $maxSteps; $c++) {
            $pos = move($grid, $startRow, $startCol, 'N', $a);
            if (!$pos) continue;
            $pos = move($grid, $pos['row'], $pos['col'], 'E', $b);
            if (!$pos) continue;
            $pos = move($grid, $pos['row'], $pos['col'], 'S', $c);
            if (!$pos) continue;

            $key = $pos['row'] . ',' . $pos['col'];
            $probablePoints[$key] = $pos;
        }
    }
}

// Output daftar koordinat probable
echo "Probable item locations:\n";
foreach ($probablePoints as $key => $point) {
    echo "  ({$point['row']}, {$point['col']})\n";
}

// Bonus: tampilkan grid dengan $ di titik probable
foreach ($probablePoints as $point) {
    $grid[$point['row']][$point['col']] = '$';
}

echo "\nGrid:\n";
foreach ($grid as $row) {
    echo implode('', $row) . "\n";
}
