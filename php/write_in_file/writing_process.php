<?php

$filePath = $argv[1];
$rowsLimit = $argv[2];
$rowString = $argv[3];
// Generate full file path
//$filePath = $outDir . DIRECTORY_SEPARATOR . sprintf("%s%d.txt", $filePrefix, $fileIndex);

$f = fopen($filePath, "w");
for ($rowIndex = 0; $rowIndex < $rowsLimit; $rowIndex++) {
    fwrite($f, sprintf("%s %d\n", $rowString, $rowIndex));
}
fclose($f);