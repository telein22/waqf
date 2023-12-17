<?php

namespace System\Core;

use Closure;

abstract class CLICommand
{
    public function write( $line )
    {
        $h = fopen("php://stdout", "w");
        fwrite($h, $line . "\n");
        fclose($h);
    }

    public function writeError( $line )
    {
        $h = fopen("php://stderr", "w");
        fwrite($h, "\e[31m" . $line . "\e[0m\n");
        fclose($h);
    }

    public function read( $placeholder, Closure $validate = null )
    {
        echo "\e[93m" . $placeholder . "\e[0m$ ";
        $data = trim(fgets(STDIN));
        if ( !$validate ) return $data;

        // else validate
        if ( !call_user_func_array($validate, [$data]) )
        {
            $this->writeError("Invalid entry please try again");
            return $this->read($placeholder, $validate);
        }

        return $data;
    }

    abstract function run( $params );
}