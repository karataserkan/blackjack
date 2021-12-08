<?php

function non_block_read($fd, &$data)
{
    $read = array($fd);
    $write = array();
    $except = array();
    $result = stream_select($read, $write, $except, 0);
    if ($result === false) {
        throw new Exception('stream_select failed');
    }
    if ($result === 0) {
        return false;
    }
    $data = stream_get_line($fd, 1);
    return true;
}

function readValue($accepted)
{
    $starttime=time();
    $resSTDIN=fopen("php://stdin", "r");
    readline_callback_handler_install('', function () {
    });
    while (1) {
        if (non_block_read(STDIN, $x)) {
            if (in_array($x, $accepted)) {
                return $x;
            }
        } else {
            echo "\r";
            echo  "Please enter number in ".(10 - (time()-$starttime))." seconds";
            if ((time()-$starttime) > 9.99) {
                break;
            }
            usleep(1);
        }
    }
}
echo readValue(['1', '2']);
