<?php
namespace app\helpers;

/**
 *
 */
class Console
{
    public static function readFromCli($accepted, $duration, $message)
    {
        $starttime=time();
        $resSTDIN=fopen("php://stdin", "r");
        readline_callback_handler_install('', function () {
        });
        while (1) {
            if (self::nonBlockRead(STDIN, $x)) {
                if (in_array($x, $accepted)) {
                    fclose($resSTDIN);
                    return $x;
                }
            } else {
                echo "\r";
                echo($duration - (time()-$starttime)) . " " . $message;
                if ((time()-$starttime) > $duration) {
                    return -1;
                }
                usleep(10);
            }
        }
    }

    public static function nonBlockRead($source, &$data)
    {
        $read = array($source);
        $write = array();
        $except = array();
        $result = stream_select($read, $write, $except, 0);
        if ($result === false) {
            throw new Exception('stream_select failed');
        }
        if ($result === 0) {
            return false;
        }
        $data = stream_get_line($source, 1);
        return true;
    }
}
