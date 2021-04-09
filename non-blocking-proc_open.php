<?php
// non-blocking-proc_open.php
// File descriptors for each subprocess.
$descriptors = [
    0 => ['pipe', 'r'], // stdin
    1 => ['pipe', 'w'], // stdout
];

$pipes = [];
$processes = [];

foreach (range(1, 3) as $i) {
    // Spawn a subprocess.
    $cmd = [];
    $cmd[] = 'php';
    $cmd[] = 'subprocess.php';
    $cmd[] =  'proc'.$i;
    $proc = proc_open($cmd, $descriptors, $procPipes);
    $processes[$i] = $proc;
    // Make the subprocess non-blocking (only output pipe).
    stream_set_blocking($procPipes[1], 0);
    $pipes[$i] = $procPipes;
}

// Run in a loop until all subprocesses finish.
/*while ($active = array_filter($processes, function($proc) { return proc_get_status($proc)['running']; })) {
    //sleep(20);
    foreach (range(1, 3) as $i) {
        usleep(10 * 1000); // 100ms
        // Read all available output (unread output is buffered).
        $str = stream_get_contents($pipes[$i][1], 4096);
        //$accumulated[$i] .= stream_get_contents($pipes[$i][1], 8);
        if ($str) {
            echo $str . PHP_EOL;
        }
    }
    //var_dump($active);
}*/


while (true) {
    $temp = [];
    foreach ($processes as $key => $process) {
        if( proc_get_status($process)['running'] === true ) {
            $temp[$key] = $process;
        }
    }

    if (count($temp) < 1) {
        break;
    }
    foreach (range(1, 3) as $i) {
        usleep(10 * 1000); // 100ms
        // Read all available output (unread output is buffered).
        $str = stream_get_contents($pipes[$i][1], 1024);
        //print_r(stream_get_meta_data($pipes[$i][1]));
        //$str  = fread($pipes[$i][1], 1024);
        if ($str) {
            printf($str);
        }
    }
}

foreach (range(1, 3) as $i) {
    while (feof($pipes[$i][1]) !== true) {
        echo "later \n";
        $str = stream_get_contents($pipes[$i][1], 1024);
        if ($str) {
            printf($str);
        }
    }
}


// Close all pipes and processes.
foreach (range(1, 3) as $i) {

    fclose($pipes[$i][1]);
    //var_dump(proc_get_status($processes[$i]));
    $return_value = proc_close($processes[$i]);
    echo "return value: " . $return_value . PHP_EOL;
}