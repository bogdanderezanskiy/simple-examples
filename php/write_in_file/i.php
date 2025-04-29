<?php

require "vendor/autoload.php";

use React\EventLoop\Loop;
use React\ChildProcess\Process;

// Out directory
$outDir = "out";

// Prefix file Name
$filePrefix = "out_";

// Row limit
$rowsLimit = 1000000;

// Files Limit
$filesLimit = 20;

// Line template
$rowString = "This is line";

$startExecutingTime = microtime(true);

// Too slowly - 177s
// for ($fileIndex = 0; $fileIndex < $filesLimit; $fileIndex++) {
//     // Generate full file path
//     $filePath = $outDir . DIRECTORY_SEPARATOR . sprintf("%s%d.txt", $filePrefix, $fileIndex);

//     // Check if file exists
//     if (!file_exists($filePath)) {
//         // Create and write empty line
//         file_put_contents($filePath, "");
//     }

//     // Write rows
//     for ($rowIndex = 0; $rowIndex < $rowsLimit; $rowIndex++) {
//         file_put_contents($filePath, sprintf("%s %d", $rowString, $rowIndex), FILE_APPEND);
//     }
// }

// 39s
// for ($fileIndex = 0; $fileIndex < $filesLimit; $fileIndex++) {
//     // Generate full file path
//     $filePath = $outDir . DIRECTORY_SEPARATOR . sprintf("%s%d.txt", $filePrefix, $fileIndex);

//     $f = fopen($filePath, "w");
//     for ($rowIndex = 0; $rowIndex < $rowsLimit; $rowIndex++) {
//         fwrite($f, sprintf("%s %d\n", $rowString, $rowIndex));
//     }
//     fclose($f);
// }

$completedProcess = 0;
$loop = Loop::get();
for ($fileIndex = 0; $fileIndex < $filesLimit; $fileIndex++) {
    // Create command string for child process
    $filePath = $outDir . DIRECTORY_SEPARATOR . sprintf("%s%d.txt", $filePrefix, $fileIndex);
    $cmd = "php writing_process.php '$filePath' $rowsLimit '$rowString'";

    $p = new Process($cmd);
    $p->start($loop);
    // Add listener on exit event
    $p->on("exit", function() use ($filesLimit, $completedProcess, $loop) {
        if ($completedProcess == $filesLimit) {
            $loop->stop();
        }
    });
}
$loop->run();

echo "Executing time: " . microtime(true) - $startExecutingTime;