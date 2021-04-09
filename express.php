<?php

$a_string = (static function () {
    $output = '';
    for ($counter = 0; $counter < 10; $counter++)
    {
        usleep(10);
        $output .= 'a';
    }
    return $output;
})();

echo $a_string;