<?php

if (!function_exists('bytes_to_human')) {

    function bytes_to_human($bytes): ?string
    {
        if(empty($bytes)) {return null;}
        $i = floor(log($bytes) / log(1024));
        $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

        return sprintf('%.01F', $bytes / pow(1024, $i)) * 1 . ' ' . $sizes[$i];
    }

}
